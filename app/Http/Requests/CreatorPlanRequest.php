<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatorPlanRequest extends FormRequest
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
            'title' => [
                'required',
                'max:255'
            ],
            'amount'=> [
                'required',
                'gte:5'
            ],
            'month_2'=> [
                'nullable',
                'array'
            ],
            'month_3'=> [
                'nullable',
                'array'
            ],
            'month_6'=> [
                'nullable',
                'array'
            ],
            'status'=> [
                'sometimes',
                'bool'
            ],
            'benefits' => [
                'nullable',
                'string',
                'max:655'
            ]
            // 'yearly'=> [
            //     'nullable',
            //     'array'
            // ], 
        ];

        if(!empty($this->promotion)){
            $rules['prom_amount'] = ['required'];
            $rules['avail_from']    = [
                'required',
                'date',
                'after_or_equal:today'
            ];
            $rules['avail_to']    = [
                'required',
                'date',
                'after:avail_from'
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
            'title.required'    => 'Please enter title of the Plan.',
            'title.max'         => 'Title can have maximum 255 characters.',
            'amount.required'   => 'Amount is required for the plan.',
            'amount.gte'        => 'Minimum value is £5.',
            'month_2.array'     => '2 Month Tier data is invalid.',
            'month_3.array'     => '3 Month Tier data is invalid.',
            'month_6.array'     => '6 Month Tier data is invalid.',
            'month_2.array'     => 'Month 2 Tier data is invalid.',
            'status.bool'       => 'Invalid Status Data.',
            'benefits.string'   => 'Please enter valid characters.',
            'benefits.max'      => 'Maximum 655 characters allowed in benefits.',
            'prom_amount.required'=> 'For Promotion please set the amount.',
            'avail_from.required' => 'Promotion Start Date Required.',
            'avail_from.date'     => 'Promotion Start Date is invalid.',
            'avail_from.after_or_equal'=> 'Promotion can be start from today or later.',
            'avail_to.required'   => 'Promotion End Date Required.',
            'avail_to.date'       => 'Promotion End Date is invalid.',
            'avail_to.after'    => 'Promotion end date should be after from start.',
        ];
    }
}
