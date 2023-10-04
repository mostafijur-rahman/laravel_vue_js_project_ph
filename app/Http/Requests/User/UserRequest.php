<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
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
            'role_id' => 'required',
            'first_name' => 'required',
        ];

        if(Request::isMethod('post')){
            $rules['phone'] = 'required|unique:users,phone';
            $rules['password'] = 'required|required_with:password_confirmation|same:password_confirmation';
        } else {
            $rules['phone'] = 'required|unique:users,phone,'. $request->input('id') .',id';
        }
        
        return $rules;
    }

    public function attributes()
    {
        return [
            'role_id' => __('cmn.role'),
            'first_name' => __('cmn.first_name'),
            'phone' => __('cmn.phone'),
            'password' => __('cmn.password'),
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => __('cmn.must_be_required'), 
            'first_name.required' => __('cmn.must_be_required'), 

            'phone.required' => __('cmn.must_be_required'), 
            'phone.unique' => __('cmn.must_be_unique'), 

            'password.required' => __('cmn.must_be_required'), 
            'password.required_with' => __('cmn.must_be_required'), 
            'password.same' => __('cmn.must_be_same'), 

        ];
    }
}
