<?php

declare(strict_types=1);

namespace App\Modules\User\Resources;

use App\Modules\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class LoggedUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'hasTeam' => $this->hasTeam(),
            'hasNewsletter' => $this->hasNewsletter(),
        ];
    }
}
