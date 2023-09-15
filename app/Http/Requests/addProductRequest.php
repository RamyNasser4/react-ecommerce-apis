<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'image' => 'required',
            'collection_name' => 'required|max:255',
            'price' => 'required',
            'details' => 'required'
        ];
    }
    public function messages(){
        return[
            'name.required' => 'A name is required',
            'image.required' => 'An image is required',
            'collection_name.required' => 'A collection is required',
            'price.required' => 'A price is required',
            'details.required' => 'Product details are required'
        ];
    }
}
