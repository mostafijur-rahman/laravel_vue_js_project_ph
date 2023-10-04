<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

use App\Traits\CommonTrait;

class OilRequest extends FormRequest
{
    use CommonTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        $rules = [
            'date_time' => 'required',
            'liter' => 'bail|required|numeric|gt:-1',
            'rate' => 'bail|required|numeric|gt:-1',
            'amount' => 'bail|required|numeric|gt:0',
            'vendor_id' => 'required'
        ];

        if($this->payment_feature() == 'enable'){
            $rules['account_id'] = 'required';
        }

        return $rules;

    }

    public function attributes()
    {
        return [
            'date_time' => __('cmn.date_time'),
            'account_id' => __('cmn.account'),
            'liter' => __('cmn.liter'),
            'rate' => __('cmn.rate'),
            'amount' => __('cmn.amount'),
            'vendor_id' => __('cmn.vendor'),
        ];
    }

    public function messages()
    {
        return [
            'date_time.required' => __('cmn.must_be_required'), 
            'account_id.required' => __('cmn.must_be_required'), 

            'liter.required' => __('cmn.must_be_required'), 
            'liter.numeric' => __('cmn.must_be_in_numeric'), 
            'liter.gt' => __('cmn.give_the_correct_amount'), 

            'rate.required' => __('cmn.must_be_required'), 
            'rate.numeric' => __('cmn.must_be_in_numeric'), 
            'rate.gt' => __('cmn.give_the_correct_amount'), 

            'amount.required' => __('cmn.must_be_required'), 
            'amount.numeric' => __('cmn.must_be_in_numeric'), 
            'amount.gt' => __('cmn.give_the_correct_amount'), 

            'vendor_id.required' => __('cmn.must_be_required'), 
        ];
    }
}
