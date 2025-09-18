<?php

namespace App\Http\Requests;

use App\DTO\PasswordResetDTO;
use Illuminate\Foundation\Http\FormRequest;

class   PasswordResetConfirmRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public const EMAIL = 'email';
    public const CODE = 'code';
    public const PASSWORD = 'password';
    public const PASSWORD_CONFIRMATION = 'password_confirm';

    public function rules(): array
    {
        return [
            self::EMAIL => [
                'required',
                'email'
            ],
            self::CODE => [
                'required',
                'int:6'
            ],
            self::PASSWORD => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Неверный формат email',
            'password.min' => 'Пароль должен иметь не менее 8 символов',
            'password.confirmed' => 'Пароль и подтверждение должны совпадать',
            'code.digits' => 'Код должен быть 6-значным',

        ];
    }

    public function toDTO(): PasswordResetDTO
    {
        return new PasswordResetDTO($this->validated());
    }

    public function getEmail(): string
    {
        return $this->get(self::EMAIL);
    }

    public function getCode(): int
    {
        return $this->get(self::CODE);
    }

    public function getPassword(): string
    {
        return $this->get(self::PASSWORD);
    }

    public function getPasswordConfirmation(): string
    {
        return $this->get(self::PASSWORD_CONFIRMATION);
    }
}


