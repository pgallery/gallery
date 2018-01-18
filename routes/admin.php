<?php

/*
 * 
 * Маршруты панели управления галереей
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

// Генерация разных страниц
Route::get('/list/{option?}/{id?}', [
    'as'            => 'admin', 
    'uses'          => 'InterfaceController@getPage',
    'middleware'    => 'role:admin|moderator'
])->where(['option', '[A-Za-z0-9]+', 'id' => '[0-9]+']);

Route::get('/wizard/step-{id}', [
    'as'            => 'wizard', 
    'uses'          => 'WizardController@getWizard',
    'middleware'    => 'role:admin'
])->where(['id' => '[0-9]+']);

// Статус фоновых задач
Route::get('/status/', [
    'as'            => 'status', 
    'uses'          => 'StatusController@getStatus',
    'middleware'    => 'role:admin'
]);


// Теги
Route::get('/tags/', [
    'as'            => 'tags', 
    'uses'          => 'TagsController@getTags',
    'middleware'    => 'role:admin'
]);
Route::post('/tags/rename/', [
    'as'            => 'rename-tag', 
    'uses'          => 'TagsController@putRename',
    'middleware'    => 'role:admin'
]);
Route::get('/tags/delete/{id}', [
    'as'            => 'delete-tag', 
    'uses'          => 'TagsController@deleteTag',
    'middleware'    => 'role:admin'
])->where(['id' => '[0-9]+']);

// Статистика
Route::get('/statistics/', [
    'as'            => 'statistics', 
    'uses'          => 'StatisticsController@getStatistics',
    'middleware'    => 'role:admin|moderator'
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
    'as'            => 'restoreCategory-trash',
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

// Категории
Route::get('/category/edit/{id}', [
    'as'            => 'edit-category',
    'uses'          => 'CategoriesController@getEditСategory',
    'middleware'    => 'role:admin'
])->where(['id' => '[0-9]+']);
Route::post('/category/save/', [
    'as'            => 'save-category',
    'uses'          => 'CategoriesController@putSaveСategory',
    'middleware'    => 'role:admin'
]);
Route::get('/category/delete/{id}', [
    'as'            => 'delete-category',
    'uses'          => 'CategoriesController@deleteСategory',
    'middleware'    => 'role:admin'
])->where(['id' => '[0-9]+']);
Route::post('/category/create/', [
    'as'            => 'create-category', 
    'uses'          => 'CategoriesController@postCreateСategory',
    'middleware'    => 'role:admin'
]);

// Альбомы
Route::get('/album/edit/{id}', [
    'as'            => 'edit-album', 
    'uses'          => 'AlbumsController@getEditAlbum',
    'middleware'    => 'role:admin|moderator'
])->where(['id' => '[0-9]+']);
Route::post('/album/save/', [
    'as'            => 'save-album', 
    'uses'          => 'AlbumsController@putSaveAlbum',
    'middleware'    => 'role:admin|moderator'
]);
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
Route::post('/album/changeowner/', [
    'as'            => 'changeowner-album', 
    'uses'          => 'AlbumsController@putChangeOwnerAlbum',
    'middleware'    => 'role:admin'
]);

// Изображения
Route::get('/image/delete/{id}', [
    'as'            => 'delete-image', 
    'uses'          => 'ImagesController@deleteImage',
    'middleware'    => 'role:admin|moderator'
])->where(['id' => '[0-9]+']);
Route::post('/image/rename/', [
    'as'            => 'rename-image', 
    'uses'          => 'ImagesController@putRename',
    'middleware'    => 'role:admin|moderator'
]);
Route::post('/image/changeowner/', [
    'as'            => 'changeowner-image', 
    'uses'          => 'ImagesController@putChangeOwnerImage',
    'middleware'    => 'role:admin'
]);
Route::post('/image/movetoalbum/', [
    'as'            => 'movetoalbum-image', 
    'uses'          => 'ImagesController@putMoveToAlbum',
    'middleware'    => 'role:admin'
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

// Загрузка изображений
Route::any('/uploads/{option?}', [
    'as'            => 'uploads',
    'uses'          => 'UploadsController@postUploads',
    'middleware'    => 'role:admin|moderator'
])->where(['option' => '[a-z]+']);
