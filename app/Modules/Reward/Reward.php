<?php

declare(strict_types=1);

namespace App\Modules\Reward;

use App\Casts\Model\UuidModelCast;
use App\Modules\ReferralProgram\ReferralProgram;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Ramsey\Uuid\UuidInterface;

/**
 * @mixin IdeHelperReward
 * @property-read ReferralProgram $rewardable
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
    ];

    /**
     * -----------------------------------------------------------------------------------------------------------------
     * Relations
     */
    public function rewardable(): MorphTo
    {
        return $this->morphTo();
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
