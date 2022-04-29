<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class FileRequest extends FormRequest
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
            
            'file' => [
                'required',
                'mimetypes:image/jpeg,image/png,video/mp4,application/x-mpegURL,video/quicktime,video/avi,video/mpeg',
                'max:20480'
            ]
        ];

        
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
            'file.required'     => 'Media is not found in the request.',
            'file.mimetypes'    => 'Error: Media content is invalid',
            'file.max'          => 'Media size can me max 20MB.',
        ];
    }

    /**
     * Prepare the data before validation
     * @return void
     */
    public function prepareForValidation(){
        $this->merge([
            'uid' => Str::uuid()
        ]);
    }
}
