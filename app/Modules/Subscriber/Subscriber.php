<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

use App\Casts\Model\UuidModelCast;
use App\Modules\Newsletter\Newsletter;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

/**
 * @mixin IdeHelperSubscriber
 */
class Subscriber extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'newsletter_id',
        'referer_subscriber_id',
        'esp_id',
        'email',
        'ref_code',
        'ref_link',
        'is_referral',
        'ref_count',
        'status',
    ];

    protected $casts = [
        'newsletter_id' => UuidModelCast::class,
        'referer_subscriber_id' => UuidModelCast::class,
        'status' => SubscriberStatus::class,
        'is_referral' => SubscriberIsReferral::class,
    ];

    protected static function booted(): void
    {
        // TODO zadbaj o to, żeby ref_code był unikalny
        // zadbaj o sytuację, gdy leci exception
        static::creating(function (Subscriber $subscriber) {
            $subscriber->ref_code = strtolower(Str::random(10));
            $subscriber->ref_link = 'https://reflyte.com/join/' . $subscriber->ref_code;
            $subscriber->ref_count = 0;
        });
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     * Relations
     */
    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Subscriber::class, 'referer_subscriber_id');
    }

    public function referer(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class, 'referer_subscriber_id');
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     * Custom methods
     */
    public function getTeamId(): UuidInterface
    {
        return $this->newsletter->team_id;
    }
}
