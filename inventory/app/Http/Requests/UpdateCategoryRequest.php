<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
        // Get the category ID from route. It could be an object or id.
        $categoryId = is_object($this->route('category')) ? $this->route('category')->id : $this->route('category');

        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $categoryId],
            'description' => ['nullable', 'string'],
        ];
    }
}
