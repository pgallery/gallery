<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
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
    'as' => 'home', 'uses' => 'User\AlbumsController@getShow'
]);
Route::get('/no_access', [
    'as' => '403', 'uses' => 'User\AlbumsController@getNoAccess'
]);
Route::get('/gallery-{url}', 'User\ImagesController@getShow')->where('url', '[А-Яа-яA-Za-z0-9]+');
Route::get('/gallery/show-{url}', 'User\ImagesController@getShowPage')->where('url', '[А-Яа-яA-Za-z0-9]+');
Route::get('/album/{option}/{id}', 'User\AlbumsController@getShow')->where(['option', '[A-Za-z0-9]+', 'id' => '[0-9]+']);

Route::post('/ulogin', 'User\UloginController@login');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});

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
    
    // Админский интерфейс
    Route::group([
        'prefix'    => 'admin', 
        'namespace' => 'Admin'], function () {
        
        // Генерация разных страниц
        Route::get('/', [
            'as'            => 'admin', 
            'uses'          => 'InterfaceController@getPage',
            'middleware'    => 'role:admin|moderator'
        ]);
        Route::get('/create/all/', [
            'as'            => 'create', 
            'uses'          => 'InterfaceController@getCreateForm',
            'middleware'    => 'role:admin|moderator'
        ]);

        // Корзина мусора
        Route::get('/users/trash/', [
            'as'            => 'users-trash', 
            'uses'          => 'TrashController@getUsers',
            'middleware'    => 'role:admin'
        ]);
        Route::get('/groups/trash/', [
            'as'            => 'groups-trash', 
            'uses'          => 'TrashController@getGroups',
            'middleware'    => 'role:admin'
        ]);
        Route::get('/restore/trash/{option}/{id}', [
            'as'            => 'restoreGroup-trash',
            'uses'          => 'TrashController@getRestore',
            'middleware'    => 'role:admin|moderator'
        ])->where(['option' => '[a-z]+', 'id' => '[0-9]+']);
        Route::get('/albums/trash/', [
            'as'            => 'albums-trash', 
            'uses'          => 'TrashController@getAlbums',
            'middleware'    => 'role:admin|moderator'
        ]);
        Route::get('/images/trash/', [
            'as'            => 'images-trash', 
            'uses'          => 'TrashController@getImages',
            'middleware'    => 'role:admin|moderator'
        ]);
        Route::get('/empty/trash/', [
            'as'            => 'empty-trash', 
            'uses'          => 'TrashController@deleteTrash',
            'middleware'    => 'role:admin'
        ]);
        
        // Очистка всего кэша
        Route::get('/flush/cache/', [
            'as'            => 'flush-cache', 
            'uses'          => 'CacheController@flushCache',
            'middleware'    => 'role:admin|moderator'
        ]);         

        // Настройки
        Route::get('/settings/', [
            'as'            => 'settings', 
            'uses'          => 'SettingsController@getSettings',
            'middleware'    => 'role:admin'
        ]);
        Route::post('/settings/save/', [
            'as'            => 'save-settings', 
            'uses'          => 'SettingsController@putSettings',
            'middleware'    => 'role:admin'
        ]);
        Route::post('/settings/create/', [
            'as'            => 'create-settings', 
            'uses'          => 'SettingsController@postSettings',
            'middleware'    => 'role:admin'
        ]);
        
        // Управление пользователями
        Route::get('/users/', [
            'as'            => 'users', 
            'uses'          => 'UsersController@getPage',
            'middleware'    => 'role:admin'
        ]);
        Route::get('/users/delete/{id}', [
            'as'            => 'delete-user', 
            'uses'          => 'UsersController@deleteUser',
            'middleware'    => 'role:admin'
        ])->where(['id' => '[0-9]+']);
        Route::get('/users/edit/{id}', [
            'as'            => 'edit-user', 
            'uses'          => 'UsersController@getEdit',
            'middleware'    => 'role:admin'
        ])->where(['id' => '[0-9]+']);        
        Route::post('/users/save/{id}', [
            'as'            => 'save-user',
            'uses'          => 'UsersController@putUser',
            'middleware'    => 'role:admin'
        ])->where(['id' => '[0-9]+']);        
        
        // Группы
        Route::get('/group/edit/{id}', [
            'as'            => 'edit-group',
            'uses'          => 'GroupsController@getEditGroup',
            'middleware'    => 'role:admin'
        ])->where(['id' => '[0-9]+']);
        Route::post('/group/save/{id}', [
            'as'            => 'save-group',
            'uses'          => 'GroupsController@putSaveEditGroup',
            'middleware'    => 'role:admin'
        ])->where(['id' => '[0-9]+']);
        Route::get('/group/delete/{id}', [
            'as'            => 'delete-group',
            'uses'          => 'GroupsController@deleteGroup',
            'middleware'    => 'role:admin'
        ])->where(['id' => '[0-9]+']);
        Route::post('/group/create/', [
            'as'            => 'create-group', 
            'uses'          => 'GroupsController@postCreateGroup',
            'middleware'    => 'role:admin'
        ]);

        // Альбомы
        Route::get('/album/edit/{id}', [
            'as'            => 'edit-album', 
            'uses'          => 'AlbumsController@getEditAlbum',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);
        Route::post('/album/edit/{id}', [
            'as'            => 'save-album', 
            'uses'          => 'AlbumsController@putSaveAlbum',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);
        Route::get('/album/delete/{id}', [
            'as'            => 'delete-album',
            'uses'          => 'AlbumsController@deleteAlbum',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);
        Route::get('/album/show/{id}', [
            'as'            => 'show-album',
            'uses'          => 'AlbumsController@getShowAlbum',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);
        Route::get('/album/sync/{id}', [
            'as'            => 'sync-album',
            'uses'          => 'AlbumsController@getSync',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);
        Route::get('/album/rebuild/{id}', [
            'as'            => 'rebuild-album',
            'uses'          => 'AlbumsController@getRebuild',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);
        Route::get('/album/uploads/{id}', [
            'as'            => 'uploads-album',
            'uses'          => 'AlbumsController@getUploads',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);        
        Route::post('/album/create/', [
            'as'            => 'create-album', 
            'uses'          => 'AlbumsController@postCreateAlbum',
            'middleware'    => 'role:admin|moderator'
        ]);

        // Изображения
        Route::get('/image/delete/{id}', [
            'as'            => 'delete-image', 
            'uses'          => 'ImagesController@deleteImage',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);
        Route::post('/image/rename/', [
            'as'            => 'rename-image', 
            'uses'          => 'ImagesController@postRename',
            'middleware'    => 'role:admin|moderator'
        ]);
        Route::get('/image/install/{id}', [
            'as'            => 'install-image', 
            'uses'          => 'ImagesController@putInstallImage',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);
        Route::get('/image/rebuild/{id}', [
            'as'            => 'rebuild-image',
            'uses'          => 'ImagesController@getRebuild',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);
        Route::get('/image/rotate/{option}/{id}', [
            'as'            => 'rotate-image',
            'uses'          => 'ImagesController@getRotate',
            'middleware'    => 'role:admin|moderator'
        ])->where(['option' => '[a-z]+', 'id' => '[0-9]+']);
        Route::post('/image/uploads/', [
            'as'            => 'uploads', 
            'uses'          => 'ImagesController@postCreateImage',
            'middleware'    => 'role:admin|moderator'
        ]);
        
    });
    
});
