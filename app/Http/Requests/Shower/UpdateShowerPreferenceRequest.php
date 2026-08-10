<?php

namespace App\Http\Requests\Shower;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\Shower\ShowerScale;

class UpdateShowerPreferenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'temperature' => ['required', Rule::in(array_keys(ShowerScale::PREFERENCE_TEMPERATURE_LEVELS))],
            'pressure' => ['required', Rule::in(array_keys(ShowerScale::PREFERENCE_PRESSURE_LEVELS))],
        ];
    }
}
