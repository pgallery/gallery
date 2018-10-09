<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

use Auth;

class ProfileRequest extends FormRequest
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
        $user = Auth::user();
        return [
            'name'   => 'required|string', 
            'email'  => [
                'required', 
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'newpassword'  => 'confirmed',
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
            'name.required'         => __('request_profile.name.required'),
            'email.required'        => __('request_profile.email.required'),
            'email.email'           => __('request_profile.email.email'),
            'email.unique'          => __('request_profile.email.unique'),
            'newpassword.confirmed' => __('request_profile.newpassword.confirmed'),
        ];
    }
}
