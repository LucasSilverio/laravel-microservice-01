<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateCompany extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', "unique:companies"],
            'whatsapp' => ['required', 'unique:companies'],
            'email' => ['required', 'email', "unique:companies"],
            'phone' => ['required', "unique:companies"],
            'facebook' => ['nullable', "unique:companies"],
            'instagram' => ['nullable', "unique:companies"],
            'youtube' => ['nullable', "unique:companies"],
        ];
    }
}
