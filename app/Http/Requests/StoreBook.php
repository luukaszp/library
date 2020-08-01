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
    public function rules() //dodać wydawnictwo i autora
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:255'],
            'description' => ['required', 'array', 'min:2'],
            'publish_year' => ['required', 'string', 'max:255'],
            'categories' => ['required', 'array', 'min:1'],
        ];
    }
}