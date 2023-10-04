<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserPassRequest extends FormRequest
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

        $rules['password'] = 'required|required_with:password_confirmation|same:password_confirmation';
        return $rules;
    }

    public function attributes()
    {
        return [
            'password' => __('cmn.password'),
        ];
    }

    public function messages()
    {
        return [
            'password.required' => __('cmn.must_be_required'), 
            'password.required_with' => __('cmn.must_be_required'), 
            'password.same' => __('cmn.must_be_same'), 

        ];
    }
}
