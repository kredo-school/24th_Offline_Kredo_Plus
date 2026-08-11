<?php

namespace App\Http\Requests\Shower;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreShowerReportRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shower_number' => ['required', 'integer', 'between:1,7'],
            'temperature' => ['required', 'integer', 'between:0,100'],
            'pressure' => ['required', 'integer', 'between:0,100'],
            'comment' => ['nullable', 'string', 'max:500'],
        ];
    }
}
