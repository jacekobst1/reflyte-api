<?php

declare(strict_types=1);

namespace App\Modules\Esp\Services;

use App\Modules\Esp\Integration\EspClientInterface;
use App\Shared\RltFields;

class EspFieldsCreator
{
    public function __construct(private readonly EspClientInterface $espClient)
    {
    }

    public function createFieldsIfNotExists(): void
    {
        $espFields = $this->espClient->getAllFields();

        foreach (RltFields::getFieldsStructure() as $field) {
            $espField = $espFields->where('name', $field['name'])->first();

            if (!$espField) {
                $this->createField($field);
            }
        }
    }

    /**
     * @param array{name: string, type: string} $field
     * @return void
     */
    private function createField(array $field): void
    {
        $this->espClient->createField($field['name'], $field['type']);
    }
}
