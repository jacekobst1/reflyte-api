<?php

declare(strict_types=1);

namespace App\Modules\Newsletter;

use App\Casts\Model\UuidModelCast;
use App\Modules\Team\Team;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperNewsletter
 */
class Newsletter extends Model
{
    use HasUuids;
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'id' => UuidModelCast::class,
        'team_id' => UuidModelCast::class,
        'esp_api_key' => 'encrypted',
    ];

    protected $fillable = [
        'name',
        'description',
        'esp_name',
        'esp_api_key',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
