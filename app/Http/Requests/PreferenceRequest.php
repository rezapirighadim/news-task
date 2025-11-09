<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PreferenceRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'preferred_sources' => 'nullable|array',
            'preferred_sources.*' => 'exists:sources,id',
            'preferred_categories' => 'nullable|array',
            'preferred_categories.*' => 'exists:categories,id',
            'preferred_authors' => 'nullable|array',
            'preferred_authors.*' => 'exists:authors,id',
        ];
    }
}
