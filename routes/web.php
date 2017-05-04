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

//Route::get('/gallery/show-{url}', 'User\ImagesController@getShowPage')->where('url', '[А-Яа-яA-Za-z0-9]+');
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
        
        // Статус фоновых задач
        Route::get('/status/', [
            'as'            => 'status', 
            'uses'          => 'StatusController@getStatus',
            'middleware'    => 'role:admin'
        ]);        
        
        // Корзина мусора
        Route::get('/trash/empty/', [
            'as'            => 'empty-trash', 
            'uses'          => 'TrashController@deleteTrash',
            'middleware'    => 'role:admin'
        ]);
        Route::get('/trash/{option}/', [
            'as'            => 'show-trash', 
            'uses'          => 'TrashController@getOptionPage',
            'middleware'    => 'role:admin|moderator'
        ])->where(['option' => '[a-z]+']);  
        Route::get('/restore/trash/{option}/{id}', [
            'as'            => 'restoreGroup-trash',
            'uses'          => 'TrashController@getRestore',
            'middleware'    => 'role:admin|moderator'
        ])->where(['option' => '[a-z]+', 'id' => '[0-9]+']);

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
        Route::post('/users/create/', [
            'as'            => 'create-users', 
            'uses'          => 'UsersController@postCreateUser',
            'middleware'    => 'role:admin'
        ]);
        Route::get('/users/delete/{id}', [
            'as'            => 'delete-user', 
            'uses'          => 'UsersController@deleteUserCheck',
            'middleware'    => 'role:admin'
        ])->where(['id' => '[0-9]+']);
        Route::post('/users/force-delete/{id}', [
            'as'            => 'force-delete-user', 
            'uses'          => 'UsersController@deleteForceUser',
            'middleware'    => 'role:admin'
        ])->where(['id' => '[0-9]+']);
        Route::post('/users/migrate-delete/{id}', [
            'as'            => 'migrate-edelete-user', 
            'uses'          => 'UsersController@deleteMigrateUser',
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
            'uses'          => 'GroupsController@putSaveGroup',
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
//        Route::resource('group', 'GroupsController', 
//                [
//                    'except'        => ['index', 'store', 'show'],
//                    'names'         => ['destroy' => 'group.delete'],
//                    'middleware'    => ['role:admin']
//                ]);

        
        // Альбомы
        Route::get('/album/edit/{id}', [
            'as'            => 'edit-album', 
            'uses'          => 'AlbumsController@getEditAlbum',
            'middleware'    => 'role:admin|moderator'
        ])->where(['id' => '[0-9]+']);
        Route::post('/album/save/{id}', [
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
