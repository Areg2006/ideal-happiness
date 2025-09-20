<?php

namespace App\Repositories;

use App\DTO\PasswordResetDTO;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PasswordRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function generatePasswordResetTokenFromDTO(PasswordResetDTO $dto): string
    {
        $digits = '0123456789';
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $digits[random_int(0, 9)];
        }

        PasswordReset::updateOrInsert(
            ['email' => $dto->email],
            [
                'token' => $code,
                'created_at' => now()
            ]
        );

        return $code;
    }

    public function verifyPasswordResetTokenFromDTO(PasswordResetDTO $dto): bool
    {
        $record = PasswordReset::where('email', $dto->email)->first();
        if (!$record) return false;

        return $record->token == $dto->code && Carbon::parse($record->created_at)->addMinutes(5)->isFuture();
    }

    public function resetPasswordFromDTO(PasswordResetDTO $dto): bool
    {
        $user = $this->findByEmail($dto->email);
        if (!$user) return false;
        $user->password = Hash::make($dto->password);
        $user->save();

        PasswordReset::where('email', $dto->email)->delete();

        return true;
    }

}
