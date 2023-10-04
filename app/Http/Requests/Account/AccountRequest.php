<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AccountRequest extends FormRequest
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

            'type' => 'required',
            'bank_id' => 'required_if:type,bank',
            'holder_name' => 'required_if:type,bank',
            'user_name' => 'required',
        ];

        if(Request::isMethod('post')){

            if( $request->input('type') == 'bank'){
                $rules['account_number'] = 'required_if:type,bank | unique:accounts,account_number,NULL,id,deleted_at,NULL';
            }

            if( $request->input('type') == 'cash'){
                $rules['user_name'] = 'required_if:type,cash | unique:accounts,user_name,NULL,id,deleted_at,NULL';
            }

        } else {

            if( $request->input('type') == 'bank'){
                $rules['account_number'] = 'required_if:type,bank | unique:accounts,account_number,' . $request->input('account_number') . ',id,deleted_at,NULL';
            }

            if( $request->input('type') == 'cash'){
                $rules['user_name'] = 'required_if:type,cash | unique:accounts,user_name,' . $request->input('user_name') . ',id,deleted_at,NULL';
            }

        }

        return $rules;

    }

    public function attributes()
    {
        return [
            'type' => __('cmn.type'),
            'bank_id' => __('cmn.bank'),

            'holder_name' => __('cmn.holder_name'),
            'user_name' => __('cmn.user_name'),
            'account_number' => __('cmn.account_number'),
            
        ];
    }

    public function messages()
    {
        return [

            'type.required' => __('cmn.must_be_required'), 
            'bank_id.required' => __('cmn.must_be_required'), 
            'holder_name.required' => __('cmn.must_be_required'),

            'account_number.required' => __('cmn.must_be_required'), 
            'account_number.unique' => __('cmn.must_be_unique'),

            'user_name.required' => __('cmn.must_be_required'), 
            'user_name.unique' => __('cmn.must_be_unique'), 
        ];
    }
}
