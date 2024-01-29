<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

use App\Casts\Model\UuidModelCast;
use App\Modules\Newsletter\Newsletter;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Reward;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;
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
        'id' => UuidModelCast::class,
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
            $subscriber->ref_link = Config::get('env.app_url') . '/join/' . $subscriber->ref_code;
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

    public function rewards(): BelongsToMany
    {
        return $this->belongsToMany(Reward::class);
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     * Custom methods
     */
    public function getTeamId(): UuidInterface
    {
        return $this->newsletter->team_id;
    }

    public function getReferralprogram(): ReferralProgram
    {
        return $this->newsletter->referralProgram;
    }
}
