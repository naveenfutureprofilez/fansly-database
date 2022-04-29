<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
            'up_img' => [
                'required',
                'mimes:jpg,bmp,png',
                'max:3072'
            ],
        ];
    }

    /**
     * Get the validation messages that apply to the rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'up_img.mimes'  => 'Inalid Image File',
            'up_img.max'    => 'Image can be maximum 3MB in size.',
            'up_img.required'=> 'Image data is empty.',
        ];
    }
}
