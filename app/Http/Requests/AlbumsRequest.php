<?php

namespace App\Http\Requests;

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
    public function rules()
    {
        return [
            'name'          => 'required|string', 
            'url'           => 'required|unique:albums',
            'directory'     => 'required|alpha_dash|unique:albums',
            'year'          => 'required|numeric',
            'groups_id'     => 'required|numeric',
        ];
    }
}
