<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBook extends FormRequest
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
            'title' => ['required', 'string', 'max:60'],
            'isbn' => ['required', 'string', 'min:13', 'max:13'],
            'description' => ['required', 'string', 'min:25'],
            'publish_year' => ['required', 'string', 'max:4'],
            //'cover' => ['required', 'string'],
        ];
    }
}
