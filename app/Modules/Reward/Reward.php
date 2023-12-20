<?php

declare(strict_types=1);

namespace App\Modules\Reward;

use App\Casts\Model\UuidModelCast;
use App\Modules\ReferralProgram\ReferralProgram;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperReward
 */
class Reward extends Model
{
    use HasUuids;
    use HasFactory;

    protected $casts = [
        'id' => UuidModelCast::class,
        'referral_program_id' => UuidModelCast::class,
    ];

    protected $fillable = [
        'name',
        'description',
        'required_points',
    ];

    public function referralProgram(): BelongsTo
    {
        return $this->belongsTo(ReferralProgram::class);
    }
}
