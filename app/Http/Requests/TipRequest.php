<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TipRequest extends FormRequest
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
            'amount' => [
                'required',
                'numeric',
                'min:5'
            ],
            'message' => [
                'sometimes',
                'nullable'
            ],
            'available' => [
                'boolean'
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
            'amount.required' => 'Please enter amount',
            'amount.numeric'  => 'Please enter a valid amount',
            'amount.min'      => 'Tip Amount should be minimum £5',
            'available.boolean' => 'Failed to check wallet balance',
        ];
    }

    /**
     * Prepare the data before validation
     * @return void
     */
    public function prepareForValidation(){
        // $user = Auth::user();
        $user = User::find(Auth::user()->id);
        $valid = $this->amount <= $user->balance ? true : false;
        $this->merge([
            'available' => $valid
        ]);
    }
}
