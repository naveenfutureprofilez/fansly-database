<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'msg' => [
                'required',
                'string',
                'max:655'
            ],
            'media' => [
                'sometimes',
                'required',
                'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,image/bmp,image/gif,image/jpeg,image/svg+xml,image/png',
                'max:204800'
            ],
            'is_locked' => [
                'sometimes',
                'required',
                'boolean'
            ],
        ];

        if(isset($this->is_locked)){
            $rules['lock_price'] = [
                'required',
                'numeric',
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
            'msg.required' => 'Please don\'t leave the message field empty',
            'msg.string'   => 'Invalid characters in message',
            'msg.max'      => 'maximum 655 characters allowed in a message',
            'media.required' => 'Media file can not be empty',
            'media.mimetypes' => 'Only Video/Image files allowed',
            'media.max'    => 'Maximum 200MB size allowed.',
            'is_locked.booled' => 'Please confirm media is locked or not.',
            'lock_price.numeric'   => 'Please enter valid amount for media lock',    
            'lock_price.required'  => 'Please enter amount for media lock',    
            'lock_price.min'   => 'Minimum £1 required for lock the media',    
        ];
    }
}
