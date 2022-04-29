<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WatermarkRequest extends FormRequest
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
            'watermark' => [
                'sometimes',
                'required',
                'string',
                'min:5',
                'max:50',
            ]
        ];
    }

    /**
     * Define validation messages for rules
     * @return array
     */
    public function messages()
    {
        return [
            'watermark.required' => 'Please enter text for watermark',
            'watermark.string'   => 'only alphanumeric text allowed',
            'watermark.min'      => 'Minimum 5 characters required',
            'watermark.max'      => 'Maximum 50 characters allowed',
        ];
    }
}
