<?php

namespace App\Http\Requests\Api\Cv;

use Illuminate\Foundation\Http\FormRequest;

class AwardRequest extends FormRequest
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
            'award_name' => 'required|string|max:100',
            'award_details' => 'required|string|max:500',
            'awarded_by' => 'required|string|max:50',
            'awarded_date' => 'nullable|date_format:Y-m-d',
        ];
    }
}
