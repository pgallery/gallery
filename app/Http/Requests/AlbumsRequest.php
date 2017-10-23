<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AlbumsRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'name'          => 'required|string', 
            'url'           => [
                'required', 
                Rule::unique('albums')->ignore($request->input('id'))
            ],
            'directory'     => [
                'required', 
                'alpha_dash', 
                Rule::unique('albums')->ignore($request->input('id'))
            ],
            'year'          => 'required|numeric',
            'categories_id' => 'required|numeric',
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
            'name.required'          => 'Поле "Название" обязательно для заполнения',
            'url.required'           => 'Поле "URL" обязательно для заполнения',
            'url.unique'             => 'Поле "URL" должно быть уникальным, возможно уже создан альбом с данным URL',
            'directory.required'     => 'Поле "Директория" обязательно для заполнения',
            'directory.alpha_dash'   => 'Поле "Директория" должно содержать только латинские символы, цифры, знаки подчёркивания (_) и дефисы (-)',
            'directory.unique'       => 'Поле "Директория" должно быть уникальным, возможно уже создан альбом с данной директорией',
            'year.required'          => 'Поле "Год" обязательно для заполнения',
            'year.numeric'           => 'Поле "Год" должно содержать только цифры',
            'categories_id.required' => 'Поле "Категория" обязательно для заполнения',
            'categories_id.numeric'  => 'Поле "Категория" должно содержать только цифры',
        ];
    }
}
