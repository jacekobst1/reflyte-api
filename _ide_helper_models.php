<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Modules\Auth\Models{use App\Modules\User\User;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Database\Eloquent\Collection;use Illuminate\Support\Carbon;
/**
 * App\Modules\Auth\Models\Permission
 *
 * @property string $uuid
 * @property string $name
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static Builder|Permission permission($permissions, $without = false)
 * @method static Builder|Permission query()
 * @method static Builder|Permission role($roles, $guard = null, $without = false)
 * @method static Builder|Permission whereCreatedAt($value)
 * @method static Builder|Permission whereGuardName($value)
 * @method static Builder|Permission whereName($value)
 * @method static Builder|Permission whereUpdatedAt($value)
 * @method static Builder|Permission whereUuid($value)
 * @method static Builder|Permission withoutPermission($permissions)
 * @method static Builder|Permission withoutRole($roles, $guard = null)
 * @mixin Eloquent
 */
	class IdeHelperPermission {}
}

namespace App\Modules\Auth\Models{use App\Modules\User\User;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Database\Eloquent\Collection;use Illuminate\Support\Carbon;
/**
 * App\Modules\Auth\Models\Role
 *
 * @property string $uuid
 * @property string $name
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role permission($permissions, $without = false)
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereGuardName($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @method static Builder|Role whereUuid($value)
 * @method static Builder|Role withoutPermission($permissions)
 * @mixin Eloquent
 */
	class IdeHelperRole {}
}

namespace App\Modules\Newsletter{use App\Modules\Esp\EspName;use App\Modules\ReferralProgram\ReferralProgram;use App\Modules\Subscriber\Subscriber;use App\Modules\Team\Team;use Database\Factories\Modules\Newsletter\NewsletterFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Database\Eloquent\Collection;use Illuminate\Support\Carbon;use Ramsey\Uuid\UuidInterface;
/**
 * App\Modules\Newsletter\Newsletter
 *
 * @property UuidInterface|null $id
 * @property UuidInterface|null $team_id
 * @property string $name
 * @property string $description
 * @property EspName $esp_name
 * @property mixed $esp_api_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read ReferralProgram|null $referralProgram
 * @property-read Collection<int, Subscriber> $subscribers
 * @property-read int|null $subscribers_count
 * @property-read Team $team
 * @method static NewsletterFactory factory($count = null, $state = [])
 * @method static Builder|Newsletter newModelQuery()
 * @method static Builder|Newsletter newQuery()
 * @method static Builder|Newsletter onlyTrashed()
 * @method static Builder|Newsletter query()
 * @method static Builder|Newsletter whereCreatedAt($value)
 * @method static Builder|Newsletter whereDeletedAt($value)
 * @method static Builder|Newsletter whereDescription($value)
 * @method static Builder|Newsletter whereEspApiKey($value)
 * @method static Builder|Newsletter whereEspName($value)
 * @method static Builder|Newsletter whereId($value)
 * @method static Builder|Newsletter whereName($value)
 * @method static Builder|Newsletter whereTeamId($value)
 * @method static Builder|Newsletter whereUpdatedAt($value)
 * @method static Builder|Newsletter withTrashed()
 * @method static Builder|Newsletter withoutTrashed()
 * @mixin Eloquent
 */
	class IdeHelperNewsletter {}
}

namespace App\Modules\ReferralProgram{use App\Modules\Newsletter\Newsletter;use App\Modules\Reward\Reward;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Database\Eloquent\Collection;use Illuminate\Support\Carbon;use Ramsey\Uuid\UuidInterface;
/**
 * App\Modules\ReferralProgram\ReferralProgram
 *
 * @property UuidInterface|null $id
 * @property UuidInterface|null $newsletter_id
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Newsletter $newsletter
 * @property-read Collection<int, Reward> $rewards
 * @property-read int|null $rewards_count
 * @method static Builder|ReferralProgram newModelQuery()
 * @method static Builder|ReferralProgram newQuery()
 * @method static Builder|ReferralProgram query()
 * @method static Builder|ReferralProgram whereCreatedAt($value)
 * @method static Builder|ReferralProgram whereId($value)
 * @method static Builder|ReferralProgram whereNewsletterId($value)
 * @method static Builder|ReferralProgram whereStatus($value)
 * @method static Builder|ReferralProgram whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class IdeHelperReferralProgram {}
}

namespace App\Modules\Reward{use App\Modules\ReferralProgram\ReferralProgram;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Support\Carbon;use Ramsey\Uuid\UuidInterface;
/**
 * App\Modules\Reward\Reward
 *
 * @property UuidInterface|null $id
 * @property UuidInterface|null $referral_program_id
 * @property string $name
 * @property string $description
 * @property int $points
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ReferralProgram $referralProgram
 * @method static Builder|Reward newModelQuery()
 * @method static Builder|Reward newQuery()
 * @method static Builder|Reward query()
 * @method static Builder|Reward whereCreatedAt($value)
 * @method static Builder|Reward whereDescription($value)
 * @method static Builder|Reward whereId($value)
 * @method static Builder|Reward whereName($value)
 * @method static Builder|Reward wherePoints($value)
 * @method static Builder|Reward whereReferralProgramId($value)
 * @method static Builder|Reward whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class IdeHelperReward {}
}

namespace App\Modules\Subscriber{use App\Modules\ReferralProgram\ReferralProgram;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Support\Carbon;
/**
 * App\Modules\Subscriber\Subscriber
 *
 * @property string $id
 * @property string $newsletter_id
 * @property string $email
 * @property string $ref_code
 * @property string $ref_link
 * @property string $is_ref
 * @property int $ref_count
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ReferralProgram $referralProgram
 * @method static Builder|Subscriber newModelQuery()
 * @method static Builder|Subscriber newQuery()
 * @method static Builder|Subscriber query()
 * @method static Builder|Subscriber whereCreatedAt($value)
 * @method static Builder|Subscriber whereEmail($value)
 * @method static Builder|Subscriber whereId($value)
 * @method static Builder|Subscriber whereIsRef($value)
 * @method static Builder|Subscriber whereNewsletterId($value)
 * @method static Builder|Subscriber whereRefCode($value)
 * @method static Builder|Subscriber whereRefCount($value)
 * @method static Builder|Subscriber whereRefLink($value)
 * @method static Builder|Subscriber whereStatus($value)
 * @method static Builder|Subscriber whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class IdeHelperSubscriber {}
}

namespace App\Modules\Team{use App\Modules\Newsletter\Newsletter;use App\Modules\User\User;use Database\Factories\Modules\Team\TeamFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Database\Eloquent\Collection;use Illuminate\Support\Carbon;use Ramsey\Uuid\UuidInterface;
/**
 * App\Modules\Team\Team
 *
 * @property UuidInterface|null $id
 * @property UuidInterface|null $owner_user_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Newsletter|null $newsletter
 * @property-read User $owner
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static TeamFactory factory($count = null, $state = [])
 * @method static Builder|Team newModelQuery()
 * @method static Builder|Team newQuery()
 * @method static Builder|Team query()
 * @method static Builder|Team whereCreatedAt($value)
 * @method static Builder|Team whereId($value)
 * @method static Builder|Team whereName($value)
 * @method static Builder|Team whereOwnerUserId($value)
 * @method static Builder|Team whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class IdeHelperTeam {}
}

namespace App\Modules\User{use App\Modules\Auth\Models\Permission;use App\Modules\Auth\Models\Role;use App\Modules\Team\Team;use Database\Factories\Modules\User\UserFactory;use Eloquent;use Illuminate\Database\Eloquent\Builder;use Illuminate\Database\Eloquent\Collection;use Illuminate\Notifications\DatabaseNotification;use Illuminate\Notifications\DatabaseNotificationCollection;use Illuminate\Support\Carbon;use Laravel\Sanctum\PersonalAccessToken;use Ramsey\Uuid\UuidInterface;
/**
 * App\Modules\User\User
 *
 * @property UuidInterface|null $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property UuidInterface|null|null $team_id
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Team|null $ownedTeam
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Team|null $team
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions, $without = false)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null, $without = false)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereTeamId($value)
 * @method static Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static Builder|User whereTwoFactorSecret($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User withoutPermission($permissions)
 * @method static Builder|User withoutRole($roles, $guard = null)
 * @mixin Eloquent
 */
	class IdeHelperUser {}
}

