<?php

declare(strict_types=1);

namespace App\Modules\Esp\Services;

use App\Modules\Esp\Integration\EspClientInterface;

final class EspFieldsCreator
{
    private const FIELDS = [
        [
            'name' => 'RLT_REF_CODE',
            'type' => 'text',
        ],
        [
            'name' => 'RLT_REF_LINK',
            'type' => 'text',
        ],
        [
            'name' => 'RLT_IS_REF',
            'type' => 'text',
        ],
        [
            'name' => 'RLT_REF_COUNT',
            'type' => 'number',
        ]
    ];

    public function __construct(private readonly EspClientInterface $espClient)
    {
    }

    public function createFieldsIfNotExists(): void
    {
        $espFields = $this->espClient->getAllFields();

        foreach (self::FIELDS as $field) {
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
