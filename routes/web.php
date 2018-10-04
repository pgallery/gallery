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

// Маршруты авторизации
Auth::routes();

// Публичные страницы, доступные без авторизации
Route::get('/', [
    'as'    => 'home', 
    'uses'  => 'User\GalleryController@getShow'
]);

Route::get('/no_access', [
    'as'    => '403', 
    'uses'  => 'User\AccessController@getNoAccess'
]);

Route::get('/images/{option}/{url}/{name}', [
    'as'    => 'show-image', 
    'uses'  => 'User\ImagesController@getImage'
]);
Route::post('/auth-album/{url}/', [
    'as'    => 'auth-album', 
    'uses'  => 'User\AlbumsController@postAuth'
]);
Route::get('/by/{option}/{url}', [
    'as'    => 'album-showBy', 
    'uses'  => 'User\GalleryController@getShow'    
])->where(['option', '[A-Za-z0-9]+', 'id' => '[А-Яа-яA-Za-z0-9_-]+']);

Route::post('/ulogin', 'User\UloginController@login');

Route::get('/tags.json', [
    'as'            => 'json-tags', 
    'uses'          => 'User\TagsController@getJSON',
]);

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
    Route::get('/downloads/{url}.zip', [
        'as'            => 'zip-album',
        'uses'          => 'User\ArchivesController@getZip',
    ])->where('url', '[А-Яа-яA-Za-z0-9_-]+');
});

Route::get('/{url}', [
    'as'    => 'gallery-show', 
    'uses'  => 'User\AlbumsController@getPage'    
])->where('url', '[А-Яа-яA-Za-z0-9_-]+');
