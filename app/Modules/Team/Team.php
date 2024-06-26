<?php

declare(strict_types=1);

namespace App\Modules\Team;

use App\Casts\Model\UuidModelCast;
use App\Modules\Newsletter\Newsletter;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperTeam
 */
class Team extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_user_id',
    ];

    protected $casts = [
        'id' => UuidModelCast::class,
        'owner_user_id' => UuidModelCast::class,
    ];

    /**
     * -----------------------------------------------------------------------------------------------------------------
     * Relations
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function newsletter(): HasOne
    {
        return $this->hasOne(Newsletter::class);
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     * Custom methods
     */
    public function getReferralProgram(): ?ReferralProgram
    {
        return $this->newsletter?->referralProgram;
    }
}
