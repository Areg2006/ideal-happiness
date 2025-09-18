<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest as BaseRequest;

class ProductUpdateRequest extends BaseRequest
{
    public const ID = 'id';
    public const NAME = 'name';
    public const PRICE = 'price';
    public const DESCRIPTION = 'description';
    public const CATEGORY_ID = 'category_id';

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

            self::ID => ['required',
                'integer',
                'exists:products,id'
            ],
            self::NAME => ['required',
                'string',
                'max:255'
            ],
            self::PRICE => ['required',
                'numeric'
            ],
            self::DESCRIPTION => ['nullable',
                'string'
            ],
            self::CATEGORY_ID => ['required',
                'integer',
                'exists:categories,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Название должно быть строкой',
            'name.max' => 'Название не может быть длиннее 255 символов',
            'price.numeric' => 'Цена должна быть числом',
            'description.string' => 'Описание должно быть строкой',
            'category_id.integer' => 'Категория должна быть числом',
        ];
    }

    public function getID(): int
    {
        return $this->get(self::ID);
    }

    public function getName(): string
    {
        return $this->get(self::NAME);
    }

    public function getPrice(): string
    {
        return $this->get(self::PRICE);
    }

    public function getDescription(): string
    {
        return $this->get(self::DESCRIPTION);
    }
    public function getCategory_id(): string
    {
        return $this->get(self::CATEGORY_ID);
    }
}
