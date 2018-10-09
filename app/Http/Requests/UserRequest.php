<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => 'required|string', 
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed',
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'         => __('request_user.name.required'),
            'name.string'           => __('request_user.name.string'),
            'email.required'        => __('request_user.email.required'),
            'email.email'           => __('request_user.email.email'),
            'email.unique'          => __('request_user.email.unique'),
            'password.required'     => __('request_user.password.required'),
            'password.confirmed'    => __('request_user.password.confirmed'),
        ];
    }
}
