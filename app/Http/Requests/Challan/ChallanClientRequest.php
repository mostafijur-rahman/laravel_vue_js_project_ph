<?php

namespace App\Http\Requests\Challan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ChallanClientRequest extends FormRequest
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
            'client_id' => 'required',
            'challan_id' => 'required',
            'contract_fare' => 'bail|required|numeric|gt:0',
            'demarage_fare' => 'bail|required|numeric|gt:-1',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'client_id' => __('cmn.client'),
            'challan_id' => __('cmn.challan_number'),
            'contract_fare' => __('cmn.contract_fare'),
            'demarage_fare' => __('cmn.demarage_fare'),
        ];
    }

    public function messages()
    {
        return [
            'client_id.required' => __('cmn.must_be_required'), 
            'challan_id.required' => __('cmn.must_be_required'), 

            'contract_fare.required' => __('cmn.must_be_required'), 
            'contract_fare.numeric' => __('cmn.must_be_in_numeric'), 
            'contract_fare.gt' => __('cmn.give_the_correct_amount'),

            'demarage_fare.required' => __('cmn.must_be_required'), 
            'demarage_fare.numeric' => __('cmn.must_be_in_numeric'), 
            'demarage_fare.gt' => __('cmn.give_the_correct_amount'),
        ];
    }
}
