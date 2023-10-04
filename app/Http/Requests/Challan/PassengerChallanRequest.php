<?php

namespace App\Http\Requests\Challan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

// use App\Traits\CommonTrait;

class PassengerChallanRequest extends FormRequest
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
            'start_date_time' => 'nullable',

            'vehicle_id' => 'nullable|array',
            'driver_id' => 'nullable|array',
            'guide_id' => 'nullable|array',
            'helper_id' => 'nullable|array',
        ];

        if(Request::isMethod('post')){
            $rules['challan_number'] = 'required | unique:challans,challan_number,NULL,id,deleted_at,NULL';
        } else {
            $rules['challan_number'] = 'required | unique:challans,challan_number,' . $request->input('id') . ',id,deleted_at,NULL';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'challan_number' => __('cmn.challan_number'),
            'start_date_time' => __('cmn.start_date_time'),

            'vehicle_id' => __('cmn.vendor'),
            'driver_id' => __('cmn.driver'),
            'guide_id' => __('cmn.driver'),
            'helper_id' => __('cmn.helper'),

            'note' => __('cmn.note'),
        ];
    }

    public function messages()
    {
        return [

            'challan_number.required' => __('cmn.must_be_required'), 
            'challan_number.unique' => __('cmn.must_be_unique'), 

            'start_date_time.required' => __('cmn.must_be_required'), 

            'vehicle_id.array' => __('cmn.must_be_in_array'),
            'driver_id.array' => __('cmn.must_be_in_array'),
            'guide_id.array' => __('cmn.must_be_in_array'),
            'helper_id.array' => __('cmn.must_be_in_array'),

            'note.string' => __('cmn.must_be_in_string'),

        ];
    }
}
