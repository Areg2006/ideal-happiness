<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest as BaseRequest;

class UserUpdateRequest extends BaseRequest
{
    public const ID = 'id';
    public const NAME = 'name';
    public const EMAIL = 'email';
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
        return
            [
                self::ID => [
                    'required',
                    'integer',
                    'min:1',
                    'exists:users,id'
                ],
                self::NAME => [
                    'required',
                    'string',
                    'max:255'
                ],
                self::EMAIL => [
                    'required',
                    'email',
                    'unique:users',
                    'email'
                ],
                self::ROLE => [
                    'required',
                    'string'
                ],
                self::BALANCE => [
                    'required',
                    'integer'
                ],
            ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Неверный формат email',
            'role.string' => 'Роль должна быть строкой'
        ];
    }

    public function getId(): int
    {
        return $this->get(self::ID);
    }

    public function getName(): string
    {
        return $this->get(self::NAME);
    }

    public function getEmail(): string
    {
        return $this->get(self::EMAIL);
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


