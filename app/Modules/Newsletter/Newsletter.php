<?php

declare(strict_types=1);

namespace App\Modules\Newsletter;

use App\Casts\Model\UuidModelCast;
use App\Modules\Esp\EspName;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Team\Team;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperNewsletter
 */
class Newsletter extends Model
{
    use HasUuids;
    use HasFactory;
    use SoftDeletes;

    // TODO add method getEspConfig() and VO
    protected $casts = [
        'id' => UuidModelCast::class,
        'team_id' => UuidModelCast::class,
        'esp_name' => EspName::class,
        'esp_api_key' => 'encrypted',
    ];

    protected $fillable = [
        'name',
        'description',
        'esp_name',
        'esp_api_key',
    ];

    /**
     * Relations
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function referralProgram(): HasOne
    {
        return $this->hasOne(ReferralProgram::class);
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(Subscriber::class);
    }

    /**
     * Custom methods
     */
    public function getEspConfig(): NewsletterEspConfig
    {
        return new NewsletterEspConfig($this->id, $this->esp_name, $this->esp_api_key);
    }
}
