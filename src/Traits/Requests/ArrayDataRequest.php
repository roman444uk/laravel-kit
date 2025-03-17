<?php

namespace LaravelKit\Traits\Requests;

use Illuminate\Validation\Validator;

trait ArrayDataRequest
{
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
