<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest as BaseRequest;

class CategoryUpdateRequest extends BaseRequest
{
    public const ID = 'id';
    public const NAME = 'name';

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
            self::ID => [
                'required',
                'integer',
                'exists:categories,id'
            ],
            self::NAME => [
                'required',
                'string',
                'max:255'
            ],
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
}
