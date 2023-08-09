<?php

namespace App\Http\Requests\Api\Cv;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class ReferenceRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'current_organization' => 'required|string|max:50',
            'designation' => 'required|string|max:60',
            'phone' => ['nullable', new PhoneNumber(), 'max:20'],
            'email' => 'required|email',
        ];
    }
}
