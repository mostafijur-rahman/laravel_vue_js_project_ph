<?php

namespace App\Http\Requests\Challan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Traits\CommonTrait;

class ChallanClientTransRequest extends FormRequest
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
            'challan_client_id' => 'required',
            'date_time' => 'required',
            'type' => 'required',
            'amount' => 'bail|required|numeric|gt:0',
        ];

        if($this->payment_feature() == 'enable'){
            $rules['account_id'] = 'required';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'challan_client_id' => __('cmn.challan_client'),
            'date_time' => __('cmn.date_time'),
            'type' => __('cmn.type'),
            'amount' => __('cmn.amount'),
            'account_id' => __('cmn.account'),
        ];
    }

    public function messages()
    {
        return [
            'challan_client_id.required' => __('cmn.must_be_required'), 
            'date_time.required' => __('cmn.must_be_required'), 
            'type.required' => __('cmn.must_be_required'), 
            'account_id.required' => __('cmn.must_be_required'), 

            'amount.required' => __('cmn.must_be_required'), 
            'amount.numeric' => __('cmn.must_be_in_numeric'), 
            'amount.gt' => __('cmn.give_the_correct_amount'),
        ];
    }
}
