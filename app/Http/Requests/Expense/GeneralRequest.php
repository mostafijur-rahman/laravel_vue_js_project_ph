<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

use App\Traits\CommonTrait;

class GeneralRequest extends FormRequest
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
            'expense_id' => 'required',
            'amount' => 'bail|required|numeric|gt:0',
        ];

        if($this->payment_feature() == 'enable'){
            $rules['account_id'] = 'required';
        }

        if($this->loan_feature() == 'enable'){

            if($request->input('account_id') == 'loan'){
                $rules['vendor_id'] = 'required';
            }
        }

        return $rules;

    }

    public function attributes()
    {
        return [
            'date_time' => __('cmn.date_time'),
            'account_id' => __('cmn.account'),
            'expense_id' => __('cmn.expense'),
            'amount' => __('cmn.amount'),
            'vendor_id' => __('cmn.vendor'),
        ];
    }

    public function messages()
    {
        return [
            'date_time.required' => __('cmn.must_be_required'), 
            'account_id.required' => __('cmn.must_be_required'), 
            'expense_id.required' => __('cmn.must_be_required'), 

            'amount.required' => __('cmn.must_be_required'), 
            'amount.numeric' => __('cmn.must_be_in_numeric'), 
            'amount.gt' => __('cmn.give_the_correct_amount'), 

            'vendor_id.required' => __('cmn.must_be_required'), 
        ];
    }
}
