<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest as BaseRequest;

class CategoryStoreRequest extends BaseRequest
{
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
            self::NAME => 'required',
                'string',
                'max:255',
                'unique:categories,name,'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название категории обязательно',
            'name.string' => 'Название категории должно быть строкой',
            'name.max' => 'Название категории не может быть длиннее 255 символов',
            'name.unique' => 'Такая категория уже существует'
        ];
    }

    public function getName(): string
    {
        return $this->get(self::NAME);
    }
}





