<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class VendorBillRequest extends FormRequest
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

    public function rules(Request $request)
    {

        $rules = [
            'purposeable_type' => 'required',
            'purposeable_id' => 'required',
            'vendor_id' => 'required',
            'bill_type' => 'required',
            'amount' => 'bail|required|numeric|gt:0',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'purposeable_type' => __('cmn.purpose'),
            'purposeable_id' => __('cmn.purpose'),
            'vendor_id' => __('cmn.vendor'),
            'bill_type' => __('cmn.bill_type'),
            'amount' => __('cmn.demarage_fare'),
        ];
    }

    public function messages()
    {
        return [
            'purposeable_type.required' => __('cmn.must_be_required'), 
            'purposeable_id.required' => __('cmn.must_be_required'), 
            'vendor_id.required' => __('cmn.must_be_required'), 
            'bill_type.required' => __('cmn.must_be_required'), 


            'amount.required' => __('cmn.must_be_required'), 
            'amount.numeric' => __('cmn.must_be_in_numeric'), 
            'amount.gt' => __('cmn.give_the_correct_amount'),
        ];
    }
}
