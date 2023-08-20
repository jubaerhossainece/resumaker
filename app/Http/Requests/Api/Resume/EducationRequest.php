<?php

namespace App\Http\Requests\Api\Resume;

use Illuminate\Foundation\Http\FormRequest;

class EducationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'study_field' => 'required|string|max:50',
            'degree' => 'required|string|max:50',
            'institution_name' => 'required|string|max:50',
            'result' => 'nullable|numeric|max:20',
            'city' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'grad_date' => 'nullable|date_format:Y-m-d',
            'is_current' => 'required|boolean',
        ];
    }
}
