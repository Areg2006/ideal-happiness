<?php

namespace App\Services;

use App\DTO\PasswordResetDTO;
use App\Mail\PasswordResetMail;
use App\Repositories\PasswordRepository;
use Illuminate\Support\Facades\Mail;

class PasswordService
{
    public function __construct(private PasswordRepository $passwordRepository)
    {
    }

    public function requestPasswordReset(PasswordResetDTO $dto): void
    {
        $code = $this->passwordRepository->generatePasswordResetTokenFromDTO($dto);
        Mail::to($dto->email)->send(new PasswordResetMail($code));

    }

    public function resetPassword(PasswordResetDTO $dto): bool
    {
        if (!$this->passwordRepository->verifyPasswordResetTokenFromDTO($dto)) {
            return false;
        }

        return $this->passwordRepository->resetPasswordFromDTO($dto);
    }
}
