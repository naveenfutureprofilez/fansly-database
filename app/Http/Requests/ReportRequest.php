<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'reason' => [
                'required'
            ],
            'explain' => [
                'nullable',
                'sometimes',
                'max:255'
            ]

        ];
    }

     /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'reason.required'   => 'Please select reason for report.',
            'explain.max'       => 'Maximum 255 characters allowed in explanations.',
        ];
    }
}
