<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatorRequest extends FormRequest
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
            'address' => [
                'array',
                'required',
                function ($attribute, $value, $fail){
                    if(empty($value['street']) OR empty($value['city']) OR empty($value['state']) OR empty($value['country']) OR empty($value['zip'])){
                        return $fail(__('All Address fields are required!'));
                    }
                }
            ],
            'social' => [
                'nullable',
                'array'
            ],
            'verify_img' => [
                'sometimes',
                'required',
                'mimes:jpg,bmp,png',
                'max:3072'
            ],
            'id_type' => [
                'required'
            ],
            'id_no' => [
                'required'
            ],
            'id_expire' => [
                'required'
            ]
        ];

        if($this->id_expire == 0){
            $rules['id_expiry'] = [
                'date'
            ];
        } else {
            $rules['id_expiry'] = ['nullable'];
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
            'address.array'     => 'Inavlid Address Data.',
            'address.required'  => 'Personal Address is required.',
            'social.array'      => 'Invalid Social Data Structure',
            'verify_img.mimes'  => 'Inalid',
            'verify_img.max'    => 'Image can be maximum 3MB in size.',
            'verify_img.required'=> 'Please upload an image of your ID.',
            'id_type.required'  => 'Please specify the ID document type.',  
            'id_number.required'=> 'Please enter the ID\'s unique number.',
            'id_expire.required'=> 'Please specify ID has any expiry date or not.',
            'id_expiry.date'    => 'Please enter valid date for ID expiry',
            // 'id_expiry.required'=> 'Please enter ID Expiry Date.',
        ];
    }
}
