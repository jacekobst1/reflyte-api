<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

use App\Modules\ReferralProgram\ReferralProgram;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSubscriber
 */
class Subscriber extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
        'newsletter_id',
        'email',
        'ref_code',
        'ref_link',
        'is_ref',
        'ref_count',
        'status',
    ];

    public function referralProgram(): BelongsTo
    {
        return $this->belongsTo(ReferralProgram::class);
    }
}
