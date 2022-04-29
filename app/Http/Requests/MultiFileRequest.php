<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MultiFileRequest extends FormRequest
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
            'files.*' => [
                'required',
                'mimetypes:image/jpeg,image/png,video/mp4,application/x-mpegURL,video/quicktime,video/avi,video/mpeg',
                'max:20480'
            ]
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
            'files.*.required'     => 'Media is not found in the request.',
            'files.*.mimetypes'    => 'Error: Media content is invalid',
            'files.*.max'          => 'Media size can me max 20MB.',
        ];
    }
}
