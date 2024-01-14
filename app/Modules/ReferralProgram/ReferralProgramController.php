<?php

declare(strict_types=1);

namespace App\Modules\ReferralProgram;

use App\Http\Controllers\Controller;
use App\Modules\ReferralProgram\Resources\ReferralProgramResource;
use App\Modules\ReferralProgram\Services\Http\ReferralProgramActivator;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class ReferralProgramController extends Controller
{
    public function getUserReferralProgram(): JsonResponse
    {
        $referralProgram = Auth::user()->getReferralProgram();
        $data = $referralProgram ? new ReferralProgramResource($referralProgram) : null;

        return JsonResp::success($data);
    }

    public function activateReferralProgram(
        ReferralProgram $referralProgram,
        ReferralProgramActivator $referralProgramActivator
    ): JsonResponse {
        $referralProgramActivator->activateReferralProgram($referralProgram);

        return JsonResp::success();
    }
}
