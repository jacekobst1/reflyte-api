<?php

declare(strict_types=1);

namespace App\Modules\ReferralProgram;

use App\Modules\Newsletter\Newsletter;
use App\Modules\Subscriber\Subscriber;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralProgram extends Model
{
    use HasUuids;
    use HasFactory;

    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(Subscriber::class);
    }
}
