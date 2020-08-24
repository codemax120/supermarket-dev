<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'name' => 'required|max:255',
            'price' => 'required|between:0,999999.99|max:255|regex:/^\d*(\.\d{1,2})?$/',
            'due_date' => 'required',
            'weight' => 'required',
            'perishable' => 'required',
            'category_id' => 'required'
        ];
    }
}
