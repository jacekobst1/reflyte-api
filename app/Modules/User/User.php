<?php

declare(strict_types=1);

namespace App\Modules\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Casts\Model\UuidModelCast;
use App\Modules\Newsletter\Newsletter;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Team\Team;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasUuids;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => UuidModelCast::class,
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'team_id' => UuidModelCast::class,
    ];

    /**
     * -----------------------------------------------------------------------------------------------------------------
     * Relations
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function ownedTeam(): HasOne
    {
        return $this->hasOne(Team::class, 'owner_user_id');
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     * Custom methods
     */
    public function getNewsletter(): ?Newsletter
    {
        return $this->team?->newsletter;
    }

    public function getReferralProgram(): ?ReferralProgram
    {
        return $this->team?->getReferralProgram();
    }

    public function hasTeam(): bool
    {
        return (bool)$this->team;
    }

    public function hasNewsletter(): bool
    {
        return (bool)$this->team?->newsletter;
    }
}
