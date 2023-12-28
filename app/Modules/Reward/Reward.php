<?php

declare(strict_types=1);

namespace App\Modules\Reward;

use App\Casts\Model\UuidModelCast;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Subscriber\Subscriber;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\UuidInterface;

/**
 * @property-read ReferralProgram $rewardable
 * @mixin IdeHelperReward
 */
class Reward extends Model
{
    use HasUuids;
    use HasFactory;

    protected $casts = [
        'id' => UuidModelCast::class,
        'rewardable_id' => UuidModelCast::class,
    ];

    protected $fillable = [
        'name',
        'description',
        'required_points',
        'mail_text',
    ];

    /**
     * -----------------------------------------------------------------------------------------------------------------
     * Relations
     */
    public function rewardable(): MorphTo
    {
        return $this->morphTo();
    }

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(Subscriber::class)->withPivot('is_sent');
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     * Custom methods
     */
    public function getTeamId(): UuidInterface
    {
        return $this->rewardable->getTeamId();
    }
}
