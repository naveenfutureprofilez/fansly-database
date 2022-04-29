<?php

namespace App\Http\Requests;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class TopupRequest extends FormRequest
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
                'min:5',
            ],
            'pmethod' => [
                'required'
            ],
            'card' => [
                'exists:payment_methods,id'
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
            'amount.min'      => 'Amount should be minimum £5',
            'method.required' => 'Please select a payment method',
            'card.exists'     => 'Selected payment does not exist in your account',
        ];
    }

    /**
     * Prepare the data before validation
     * @return void
     */
    public function prepareForValidation(){
        $user = Auth::user();
        $card = PaymentMethod::where('id', decrypt($this->pmethod))
        ->where('user_id', $user->id)
        ->first();
        $id = !empty($card->id) ? $card->id : 0;
        $this->merge([
            'card' => $id
        ]);
    }
}
