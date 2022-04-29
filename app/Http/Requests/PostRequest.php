<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'content' => [
                'nullable',
                'string',
                'max:655'
            ]
        ];

        // if(strlen($this->content) == 0){
        //     $rules['media'] = ['required'];
        // } else if(empty($this->media)){
        //     $rules['media'] = ['nullable'];
        // } else

        if(!empty($this->schedule)){
            $rules['schedule'] = [
                'date',
                'after_or_equal:today'
            ];
        }

        if(!empty($this->delete)){
            $rules['delete'] = ['date'];
            if(!empty($this->schedule)){
                $rules['delete'] = [
                    'date',
                    'after_or_equal:schedule'
                ];
            } else {
                $rules['delete'] = [
                    'date',
                    'after_or_equal:today'
                ];
            }
        }

        if(!empty($this->media)){
            // $rules['media'] = ['array'];
        }

        if(!empty($this->preview)){
            // $rules['preview'] = ['array'];
        }

        if(!empty($this->condition)){
            $rules['condition'] = ['array'];
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
        $msgs = [
            'content.string'    => 'Content is not valid.',
            'content.max'       => 'Maximum 655 characters allowed.',
            'media.required'    => 'Text or Media Required for the post.',
            'media.array'       => 'Media data set is invalid.',
            'preview.array'     => 'Media preview data set is invalid.',
            'condition.array'   => 'Post conditions are invalid.',
            'schedule.date'     => 'Post Schedule Date is invalid.',
            'schedule.after_or_equal'    => 'Post Schedule Date should be today or upcoming days.',
            'delete.date'      => 'Post auto delete Date is invalid.',
            // 'post_delete.after'     => 'Post auto delete Date should be after Post Schedule Date or Today.',
        ];

        if(!empty($this->delete)){
            if(!empty($this->schedule)){
                $msgs['delete.after_or_equal'] = 'Post auto delete Date should be after or equal to Post Schedule Date.';
            } else {
                $msgs['delete.after_or_equal'] = 'Post auto delete Date should be after or equal to Today.';
            }
        }
        return $msgs;
    }
}
