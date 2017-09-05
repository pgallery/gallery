<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlbumsEditRequest extends FormRequest
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
            'name'          => 'required|string', 
//            'url'           => 'required|unique:albums',
            'url'           => 'required',
            'year'          => 'required|numeric',
            'groups_id'     => 'required|numeric',
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
            'name.required'         => 'Поле "Название" обязательно для заполнения',
            'url.required'          => 'Поле "URL" обязательно для заполнения',
//            'url.unique'            => 'Поле "URL" должно быть уникальным, возможно уже создан альбом с данным URL',
            'year.required'         => 'Поле "Год" обязательно для заполнения',
            'year.numeric'          => 'Поле "Год" должно содержать только цифры',
            'groups_id.required'    => 'Поле "Группа" обязательно для заполнения',
            'groups_id.numeric'     => 'Поле "Группа" должно содержать только цифры',
        ];
    }
}
