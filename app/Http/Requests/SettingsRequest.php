<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
            'set_name'   => 'required|alpha_dash|unique:settings', 
            'set_value'  => 'required|string',
            'set_desc'   => 'required|string',
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
            'set_name.required'    => 'Поле "Наименование ключа" обязательно для заполнения',
            'set_name.alpha_dash'  => 'Поле "Наименование ключа" должно содержать только латинские символы, цифры, знаки подчёркивания (_) и дефисы (-)',
            'set_name.unique'      => 'Поле "Наименование ключа" должно быть уникальным, возможно уже существует данный ключ',
            'set_value.required'   => 'Поле "Значение" обязательно для заполнения',
            'set_desc.required'    => 'Поле "Описание" обязательно для заполнения',
        ];
    }
}
