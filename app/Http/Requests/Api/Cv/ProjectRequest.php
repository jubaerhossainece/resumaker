<?php

namespace App\Http\Requests\Api\Cv;

use App\Rules\ValidUrl;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'name' => 'required|string|max:60',
            'detail' => 'nullable|string|max:500',
            'project_url'=> ['nullable', new ValidUrl, 'max:100'],
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'no_expiry' => 'nullable|boolean'
        ];
    }
}
