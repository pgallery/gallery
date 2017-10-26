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
            'name.required'          => __('request_albums.name.required'),
            'url.required'           => __('request_albums.url.required'),
            'url.unique'             => __('request_albums.url.unique'),
            'directory.required'     => __('request_albums.directory.required'),
            'directory.alpha_dash'   => __('request_albums.directory.alpha_dash'),
            'directory.unique'       => __('request_albums.directory.unique'),
            'year.required'          => __('request_albums.year.required'),
            'year.numeric'           => __('request_albums.year.numeric'),
            'categories_id.required' => __('request_albums.categories_id.required'),
            'categories_id.numeric'  => __('request_albums.categories_id.numeric'),
        ];
    }
}
