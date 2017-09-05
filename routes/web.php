<?php

/*
 * 
 * Маршруты пользовательского интерфейса галереи
 * 
 */

/*
 * 
 * POST  — создать новую запись
 * GET — получить запись
 * PUT — редактирование записи
 * DELETE — удаление записи
 * 
 * 
 */

// Публичные страницы, доступные без авторизации
Route::get('/', [
    'as'    => 'home', 
    'uses'  => 'User\AlbumsController@getShow'
]);

Route::get('/no_access', [
    'as'    => '403', 
    'uses'  => 'User\AlbumsController@getNoAccess'
]);

Route::get('/gallery-{url}', [
    'as'    => 'gallery-show', 
    'uses'  => 'User\ImagesController@getPage'    
])->where('url', '[А-Яа-яA-Za-z0-9_-]+');

Route::get('/album/{option}/{id}', [
    'as'    => 'album-showBy', 
    'uses'  => 'User\AlbumsController@getShow'    
])->where(['option', '[A-Za-z0-9]+', 'id' => '[0-9]+']);

Route::post('/ulogin', 'User\UloginController@login');

// Маршруты авторизации
Auth::routes();

// Маршруты авторизованных пользователей
Route::group(['middleware' => 'auth'], function () {
    
    // Работа с личным профилеме
    Route::get('/profile/', [
        'as'            => 'edit-profile', 
        'uses'          => 'User\ProfileController@getProfile',
    ]);
    Route::post('/save/profile/', [
        'as'            => 'save-profile', 
        'uses'          => 'User\ProfileController@putProfile',
    ]);
    
});
