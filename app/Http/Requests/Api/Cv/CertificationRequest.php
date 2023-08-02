<?php

namespace App\Http\Requests\Api\Cv;

use App\Rules\ValidUrl;
use Illuminate\Foundation\Http\FormRequest;

class CertificationRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'issuing_org' => 'required|string|max:50',
            'credential_url' => ['nullable', new ValidUrl, 'max:100'],
            'issue_date' => 'required|date_format:Y-m-d',
            'exp_date' => 'nullable|date_format:Y-m-d',
            'is_no_exp' => 'required|boolean',
        ];
    }
}
