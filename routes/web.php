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
//Route::get('/install', function () {
//    $exitCode = Artisan::call('migrate');
//});

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
    Route::post('/profile/save/', [
        'as'            => 'save-profile', 
        'uses'          => 'User\ProfileController@putProfile',
    ]);
    
    Route::get('/profile/enable2fa/', [
        'as'            => 'enable2fa-profile', 
        'uses'          => 'User\ProfileController@getEnable2FA',
    ]);
    Route::get('/profile/disabled2fa/', [
        'as'            => 'disabled2fa-profile', 
        'uses'          => 'User\ProfileController@getDisable2FA',
    ]);    
    Route::post('/profile/disabled2fa/', [
        'as'            => 'disabled2fa-profile', 
        'uses'          => 'User\ProfileController@getDisable2FA',
    ]);
    Route::get('/google2fa/authenticate', [
        'as'            => 'google2fa.auth', 
        'uses'          => 'User\Google2FAController@g2faAuth'
    ]);
    Route::post('/google2fa/authenticate', [
        'as'            => 'google2fa.auth', 
        'uses'          => 'User\Google2FAController@g2faAuth'
    ]);    
    
    // Скачивание архива альбома
    Route::get('/downloads/{url}', [
        'as'            => 'zip-album',
        'uses'          => 'User\AlbumsController@getZip',
    ])->where('url', '[А-Яа-яA-Za-z0-9_-]+');
});
