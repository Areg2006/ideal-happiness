<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest as BaseRequest;

class UserStoreRequest extends BaseRequest
{
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';
    public const ROLE = 'role';
    public const BALANCE = 'balance';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            self::NAME => [
                'required',
                'string',
                'max:255'
            ],
            self::EMAIL => ['required',
                'email',
                'unique:users',
                'email'
            ],
            self::PASSWORD => ['required', '
            string',
                'min:6'
            ],
            self::ROLE => ['required',
                'string'
            ],
            self::BALANCE => ['required',
                'integer'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно',
            'email.required' => 'Email обязателен',
            'email.email' => 'Неверный формат email',
            'password.required' => 'Пароль обязателен',
            'role.required' => 'Роль обязательна',
            'role.string' => 'Роль должна быть строкой',
            'balance.required' => 'integer'
        ];
    }

    public function getName(): string
    {
        return $this->get(self::NAME);
    }

    public function getEmail(): string
    {
        return $this->get(self::EMAIL);
    }

    public function getPassword(): string
    {
        return $this->get(self::PASSWORD);
    }

    public function getRole(): string
    {
        return $this->get(self::ROLE);
    }

    public function getBalance(): string
    {
        return $this->get(self::BALANCE);
    }
}
