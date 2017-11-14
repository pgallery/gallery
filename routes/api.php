<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/admin/photo/add', 'PhotoController@Add');

Route::prefix('v1')->group(function () {
    
    Route::get('/', [
        'as'            => 'api', 
        'uses'          => 'ApiController@getPage',
    ]);

    Route::get('/gallery', [
        'as'            => 'api-gallery', 
        'uses'          => 'GalleryController@getPage',
    ]);
    Route::get('/album', [
        'as'            => 'api-album', 
        'uses'          => 'AlbumController@getPage',
    ]);    
});