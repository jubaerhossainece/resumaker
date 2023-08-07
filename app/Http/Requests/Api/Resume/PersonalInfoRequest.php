<?php

namespace App\Http\Requests\Api\Resume;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class PersonalInfoRequest extends FormRequest
{
    public function prepareForValidation() {
        $social_links = json_decode($this->input('social_links'), true);
        $this->merge([
            'social_links' => $social_links
        ]);

    }

    
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
            'image' => 'nullable|image',
            'first_name' => 'required|string|max:15',
            'last_name' => 'required|string|max:15',
            'profession' => 'required|string|max:60',
            'email' => 'required|email|max:40',
            'phone' => [new PhoneNumber(),'max:20'],
            'city' => 'nullable|string|max:60',
            'country' => 'nullable|string|max:60',
            'post_code' => 'required|string|max:15',
            'about' => 'required|string|max:500',
            'social_links' => 'required',
        ];
    }
}
