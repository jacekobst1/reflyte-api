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
                'key' => 'rlt_is_referral',
                'type' => 'text',
            ],
            [
                'key' => 'rlt_referrer_subscriber_id',
                'type' => 'text',
            ]
        ];
    }

    /**
     * @return array{
     *     rlt_ref_code: string,
     *     rlt_ref_link: string,
     *     rlt_is_referral: string,
     *     rlt_referrer_id: string
     * }
     */
    public static function getSubscriberFields(Subscriber $subscriber): array
    {
        return [
            'rlt_ref_code' => $subscriber->ref_code,
            'rlt_ref_link' => $subscriber->ref_link,
            'rlt_is_referral' => $subscriber->is_referral->value,
            'rlt_referrer_id' => $subscriber->referer_subscriber_id?->toString(),
        ];
    }
}
