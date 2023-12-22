<?php

declare(strict_types=1);

namespace App\Modules\ReferralProgram;

use App\Casts\Model\UuidModelCast;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Reward\Reward;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin IdeHelperReferralProgram
 */
class ReferralProgram extends Model
{
    use HasUuids;
    use HasFactory;

    protected $casts = [
        'id' => UuidModelCast::class,
        'team_id' => UuidModelCast::class,
        'newsletter_id' => UuidModelCast::class,
    ];

    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }

    public function rewards(): MorphMany
    {
        return $this->morphMany(Reward::class, 'rewardable');
    }
}
