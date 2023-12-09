<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Services\Internal;

use App\Modules\ESP\Integration\ClientInterface;

final class FieldsSynchronizer
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

    public function __construct(private readonly ClientInterface $espClient)
    {
    }

    public function sync(): void
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
