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
            'name.required'         => 'Поле "Имя" обязательно для заполнения',
            'email.required'        => 'Поле "E-Mail" обязательно для заполнения',
            'email.email'           => 'Поле "E-Mail" должно быть корректным E-Mail адресом',
            'email.unique'          => 'Поле "E-Mail" должно быть уникальным, возможно указанный Вами E-Mail уже используется',
            'newpassword.confirmed' => 'Указанные пароли не совпадают',
        ];
    }    
}
