<?php

namespace App\Http\Requests\Api\Cv;

use App\Rules\ValidUrl;
use Illuminate\Foundation\Http\FormRequest;

class PublicationRequest extends FormRequest
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
            'publication_title' => 'required|string|max:100',
            'publisher' => 'required|string|max:50',
            'published_in' => 'nullable|string|max:60',
            'publication_url' => ['nullable', new ValidUrl, 'max:100'],
            'publication_date' => 'nullable|date_format:Y-m-d',
            'description' => 'nullable|string|max:500',
        ];
    }
}
