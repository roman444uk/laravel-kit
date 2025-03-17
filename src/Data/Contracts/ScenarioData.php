<?php

namespace App\Data\Contracts;

interface ScenarioData
{
    public static function exceptFields(string $scenario = null): array;

    public function applyScenario(?string $scenario = null): static;
}
