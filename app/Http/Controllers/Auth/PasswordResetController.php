<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\PasswordResetConfirmRequest;
use App\Services\PasswordService;

class PasswordResetController extends Controller
{
    public function __construct(private PasswordService $passwordService)
    {
    }

    public function requestReset(PasswordResetRequest $request)
    {
        $dto = $request->toDTO();
        $this->passwordService->requestPasswordReset($dto);

        return response()->json(['message' => 'Verification code sent to email']);
    }

    public function resetPassword(PasswordResetConfirmRequest $request)
    {
        $dto = $request->toDTO();
        $success = $this->passwordService->resetPassword($dto);

        if (!$success) {
            return response()->json(['error' => 'Invalid or expired code'], 400);
        }

        return response()->json(['message' => 'Password successfully reset']);
    }
}
