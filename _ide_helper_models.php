<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Modules\Auth\Models{
/**
 * App\Modules\Auth\Models\Permission
 *
 * @property string $uuid
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Auth\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\User\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	class IdeHelperPermission {}
}

namespace App\Modules\Auth\Models{
/**
 * App\Modules\Auth\Models\Role
 *
 * @property string $uuid
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Auth\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\User\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role withoutPermission($permissions)
 * @mixin \Eloquent
 */
	class IdeHelperRole {}
}

namespace App\Modules\Newsletter{
/**
 * App\Modules\Newsletter\Newsletter
 *
 * @property \Ramsey\Uuid\UuidInterface|null $id
 * @property \Ramsey\Uuid\UuidInterface|null $team_id
 * @property string $name
 * @property string $description
 * @property string $landing_url
 * @property \App\Modules\Esp\EspName $esp_name
 * @property mixed $esp_api_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Modules\ReferralProgram\ReferralProgram|null $referralProgram
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Subscriber\Subscriber> $subscribers
 * @property-read int|null $subscribers_count
 * @property-read \App\Modules\Team\Team $team
 * @method static \Database\Factories\Modules\Newsletter\NewsletterFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter query()
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter whereEspApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter whereEspName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter whereLandingUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Newsletter withoutTrashed()
 * @mixin \Eloquent
 */
	class IdeHelperNewsletter {}
}

namespace App\Modules\ReferralProgram{
/**
 * App\Modules\ReferralProgram\ReferralProgram
 *
 * @property \Ramsey\Uuid\UuidInterface|null $id
 * @property \Ramsey\Uuid\UuidInterface|null $newsletter_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\Newsletter\Newsletter $newsletter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Reward\Reward> $rewards
 * @property-read int|null $rewards_count
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram whereNewsletterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperReferralProgram {}
}

namespace App\Modules\Reward{
/**
 * App\Modules\Reward\Reward
 *
 * @property \Ramsey\Uuid\UuidInterface|null $id
 * @property \Ramsey\Uuid\UuidInterface|null $referral_program_id
 * @property string $name
 * @property string $description
 * @property int $required_points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\ReferralProgram\ReferralProgram $referralProgram
 * @method static \Illuminate\Database\Eloquent\Builder|Reward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereReferralProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereRequiredPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reward whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperReward {}
}

namespace App\Modules\Subscriber{
/**
 * App\Modules\Subscriber\Subscriber
 *
 * @property string $id
 * @property \Ramsey\Uuid\UuidInterface|null $newsletter_id
 * @property \Ramsey\Uuid\UuidInterface|null|null $referer_subscriber_id
 * @property string $email
 * @property string $ref_code
 * @property string $ref_link
 * @property string $is_ref
 * @property int $ref_count
 * @property \App\Modules\Subscriber\SubscriberStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\Newsletter\Newsletter $newsletter
 * @property-read \App\Modules\ReferralProgram\ReferralProgram $referralProgram
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Subscriber> $referrals
 * @property-read int|null $referrals_count
 * @method static \Database\Factories\Modules\Subscriber\SubscriberFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereIsRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereNewsletterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereRefCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereRefCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereRefLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereRefererSubscriberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSubscriber {}
}

namespace App\Modules\Team{
/**
 * App\Modules\Team\Team
 *
 * @property \Ramsey\Uuid\UuidInterface|null $id
 * @property \Ramsey\Uuid\UuidInterface|null $owner_user_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\Newsletter\Newsletter|null $newsletter
 * @property-read \App\Modules\User\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\User\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\Modules\Team\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereOwnerUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTeam {}
}

namespace App\Modules\User{
/**
 * App\Modules\User\User
 *
 * @property \Ramsey\Uuid\UuidInterface|null $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property \Ramsey\Uuid\UuidInterface|null|null $team_id
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Modules\Team\Team|null $ownedTeam
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Auth\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Auth\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Modules\Team\Team|null $team
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\Modules\User\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

