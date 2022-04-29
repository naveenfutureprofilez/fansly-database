<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrMsgSetRequest extends FormRequest
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
        $rules = [
            'msg_setting' => [
                'sometimes',
                'required',
                'digits_between:0,2'
            ]
        ];

        if(isset($this->msg_setting) AND $this->msg_setting == 1){
            $rules['amount'] = [
                'required',
                'min:1'
            ];
        }

        return $rules;
    }

    /**
     * Get the validation messages that apply to the rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'msg_setting.required' =>  'Please select an option for message settings',
            'msg_setting.digits_between' => 'Please select a valid option for settings',
            'amount.required' => 'Please enter amount for paid messages',
            'amount.min' => 'Minimum amount should be £1'
        ];
    }
}
