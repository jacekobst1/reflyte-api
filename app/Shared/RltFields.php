<?php

declare(strict_types=1);

namespace App\Shared;

use App\Modules\Subscriber\Subscriber;

final class RltFields
{
    public static function getFieldsStructure(): array
    {
        return [
            [
                'key' => 'rlt_ref_code',
                'type' => 'text',
            ],
            [
                'key' => 'rlt_ref_link',
                'type' => 'text',
            ],
            [
                'key' => 'rlt_is_ref',
                'type' => 'text',
            ],
            [
                'key' => 'rlt_ref_count',
                'type' => 'number',
            ]
        ];
    }

    public static function getSubscriberFields(Subscriber $subscriber): array
    {
        return [
            'rlt_ref_code' => $subscriber->ref_code,
            'rlt_ref_link' => $subscriber->ref_link,
            'rlt_is_ref' => $subscriber->is_ref,
            'rlt_ref_count' => $subscriber->ref_count,
        ];
    }
}
