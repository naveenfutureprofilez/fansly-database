<?php

namespace App\Http\Requests;

use App\Models\CreatorPlan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PlanPromotionRequest extends FormRequest
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
            'planId' => [
                'required'
            ],
            'prom_amount' => [
                'required'
            ],
            'avail_from' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'avail_to' => [
                'required',
                'date',
                'after:avail_from'
            ],
            'status' => [
                'sometimes',
                'bool'
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
            'planId.required'   => 'Invalid Parameters for Plan.',
            'prom_amount.required'=> 'For Promotion please set the amount.',
            'avail_from.required' => 'Promotion Start Date Required.',
            'avail_from.date'     => 'Promotion Start Date is invalid.',
            'avail_from.after_or_equal'=> 'Promotion can be start from today or later.',
            'avail_to.required'   => 'Promotion End Date Required.',
            'avail_to.date'       => 'Promotion End Date is invalid.',
            'avail_to.after'    => 'Promotion end date should be after from start.',
            'status.bool'       => 'Invalid Status Data.',
        ];
    }

    /**
     * Prepare the data before validation
     * @return void
     */
    public function prepareForValidation(){
        $user = Auth::user();
        $user_id = $user->id;
        if(!empty($this->plan)){
            $plan = CreatorPlan::select('id')
            ->where('user_id', $user_id)
            ->where('id' , decrypt($this->plan))
            ->first();
            if($plan){
                $this->merge([
                    'planId' => $plan->id
                ]);
            }
        }
    }
}
