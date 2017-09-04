<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlbumsDirectoryRequest extends FormRequest
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
            'directory'     => 'required|alpha_dash|unique:albums',
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
            'directory.required'    => 'Имя директории не должно быть пустым',
            'directory.alpha_dash'  => 'Имя директории должно содержать только латинские символы, цифры, знаки подчёркивания (_) и дефисы (-)',
            'directory.unique'      => 'Имя директории должно быть уникальным, возможно уже создан альбом с данной директорией',
        ];
    }
}
