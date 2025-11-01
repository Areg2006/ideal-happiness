<?php

namespace App\Http\Requests;

use App\DTO\PasswordResetDTO;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public const EMAIL = 'email';

    public function rules(): array
    {
        return [
            self::EMAIL => [
                'required',
                'email',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'email' => 'Неверный формат email'
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
}
