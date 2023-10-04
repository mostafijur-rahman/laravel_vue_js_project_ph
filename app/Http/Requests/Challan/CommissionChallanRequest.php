<?php

namespace App\Http\Requests\Challan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

// use App\Traits\CommonTrait;

class CommissionChallanRequest extends FormRequest
{
    // use CommonTrait;

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
            // 'challan_number' => 'bail|required',
            'voucher_number' => 'nullable|string',

            'start_date_time' => 'nullable',
            'account_take_date_time' => 'nullable',

            'box' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'unit_id' => 'nullable|integer',
            'goods' => 'nullable|string',

            'buyer_name' => 'nullable|string',
            'buyer_code' => 'nullable|string',
            'order_no' => 'nullable|string',

            'depu_change_bill' => 'nullable|numeric|gt:-1',

            'gate_pass_no' => 'nullable|string',
            'lock_no' => 'nullable|string',
            'load_point_reach_time' => 'nullable',
            'note' => 'nullable|string',

            'vehicle_id' => 'nullable|array',
            'driver_id' => 'nullable|array',
            'helper_id' => 'nullable|array',

            'load_ids' => 'nullable|array', // array
            'unload_ids' => 'nullable|array', // array

        ];


        if(Request::isMethod('post')){
            $rules['challan_number'] = 'required | unique:challans,challan_number,NULL,id,deleted_at,NULL';
            // $rule['account_take_date'] =  'required | date_format:d/m/Y';
        } else {
            $rules['challan_number'] = 'required | unique:challans,challan_number,' . $request->input('id') . ',id,deleted_at,NULL';
        }

        return $rules;

    }

    public function attributes()
    {
        return [
            'challan_number' => __('cmn.challan_number'),
            'voucher_number' => __('cmn.voucher_number'),

            'start_date_time' => __('cmn.start_date_time'),
            'account_take_date_time' => __('cmn.amount'),

            'box' => __('cmn.box_qty'),
            'weight' => __('cmn.weight'),
            'unit_id' => __('cmn.unit'),
            'goods' => __('cmn.goods'),

            'buyer_name' => __('cmn.buyer_name'),
            'buyer_code' => __('cmn.buyer_code'),
            'order_no' => __('cmn.order_no'),

            'depu_change_bill' => __('cmn.depu_change_bill'),

            'gate_pass_no' => __('cmn.gate_pass_no'),
            'lock_no' => __('cmn.lock_no'),
            'load_point_reach_time' => __('cmn.load_point_reach_time'),
            'note' => __('cmn.note'),

            'vehicle_id' => __('cmn.vendor'),
            'driver_id' => __('cmn.driver'),
            'helper_id' => __('cmn.helper'),

            'load_ids' => __('cmn.load_point'),
            'unload_ids' => __('cmn.unload_point'),

        ];
    }

    public function messages()
    {
        return [

            'challan_number.required' => __('cmn.must_be_required'), 
            'challan_number.unique' => __('cmn.must_be_unique'), 

            'voucher_number.string' => __('cmn.must_be_in_string'), 

            'box.string' => __('cmn.must_be_in_string'), 
            'weight.string' => __('cmn.must_be_in_string'), 
            'unit_id.string' => __('cmn.must_be_in_string'), 
            'goods.string' => __('cmn.must_be_in_string'),

            'buyer_name.string' => __('cmn.must_be_in_string'), 
            'buyer_code.string' => __('cmn.must_be_in_string'), 
            'order_no.string' => __('cmn.must_be_in_string'),
            
            'depu_change_bill.numeric' => __('cmn.must_be_in_string'), 
            'depu_change_bill.gt' => __('cmn.give_the_correct_amount'), 

            'gate_pass_no.string' => __('cmn.must_be_in_string'), 
            'lock_no.string' => __('cmn.must_be_in_string'), 
            'note.string' => __('cmn.must_be_in_string'),

            'vehicle_id.array' => __('cmn.must_be_in_string'),
            'driver_id.array' => __('cmn.must_be_in_string'),
            'helper_id.array' => __('cmn.must_be_in_string'),

            'load_ids.integer' => __('cmn.must_be_in_string'),
            'unload_ids.integer' => __('cmn.must_be_in_string'),

            // 'load_ids.array' => __('cmn.must_be_in_array'),
            // 'unload_ids.array' => __('cmn.must_be_in_array'),

        ];
    }
}
