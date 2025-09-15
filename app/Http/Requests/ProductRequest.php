<?php

namespace App\Http\Requests;

class ProductRequest extends BaseRequest
{
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название обязательно',
            'name.string' => 'Название должно быть строкой',
            'name.max' => 'Название не может быть длиннее 255 символов',
            'price.required' => 'Цена обязательна',
            'price.numeric' => 'Цена должна быть числом',
            'description.string' => 'Описание должно быть строкой',
            'category_id.required' => 'Категория обязательна',
            'category_id.integer' => 'Категория должна быть числом',
            'category_id.exists' => 'Выбранная категория не существует',
        ];
    }
}
