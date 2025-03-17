<?php

namespace LaravelKit\Traits\Requests;

use Illuminate\Validation\Validator;

trait BulkRequest
{
    public function bulkRules(array $rules, array $subRuleFields = [], ?array $data = null, array $prefix = []): array
    {
        // Change validation rules principle because of slow array validation when used asterisk (*) sign for pointing
        // out array keys: https://github.com/laravel/framework/issues/49375

        $self = $this;

        $allRules = [];
        collect($data ?? $this->all())
            ->map(function (array $rowData, int $index) use ($self, $rules, $subRuleFields, $prefix, &$allRules) {
                $newRules = self::createSubRules($rules, array_merge($prefix, [$index]), $subRuleFields);

                collect($subRuleFields)->each(function ($subRuleField) use ($self, $rules, $prefix, &$newRules, $rowData, $index) {
                    $newRules = array_merge(
                        $newRules,
                        $self::bulkRules($rules[$subRuleField], [], $rowData[$subRuleField], array_merge($prefix, [$index, $subRuleField]))
                    );
                });

                $allRules = array_merge($allRules, $newRules);
            })->toArray();

        return $allRules;
    }

    protected function createSubRules(array $rules, array $prefix, array $except = []): array
    {
        $prefix = implode('.', array_filter($prefix, function ($item) {
            return $item !== '';
        }));

        $rules = array_filter($rules, function (string $fieldName) use ($except) {
            return !in_array($fieldName, $except);
        }, ARRAY_FILTER_USE_KEY);

        $newRules = array_map(
            function (string $fieldName) use ($prefix) {
                return $prefix . '.' . $fieldName;
            }, array_filter(array_keys($rules), function ($rule) use ($except) {
                return !in_array($rule, $except);
            })
        );

        return array_combine($newRules, $rules);
    }

    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            foreach ($validator->errors()->messages() as $attributeKey => $messages) {
                $validator->errors()->forget($attributeKey);

                $attributeKey = implode('.', array_reverse(explode('.', $attributeKey)));
                $validator->errors()->add($attributeKey, $messages);
            }
        });
    }
}
