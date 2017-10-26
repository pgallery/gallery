
{!! Form::open([
    'route'     => 'create-album',
    'class'     => 'form-horizontal',
    'method'    => 'POST'
]) !!}

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <div class="form-group">
            <label class="col-sm-2 control-label">Название:</label>
            <div class="col-sm-4">
                
                {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'album_name', 'required')) !!}
                
            </div>
            <label class="col-sm-2 control-label">Год:</label>
            <div class="col-sm-4">
                
                {!! Form::selectYear('year', date('Y'), Setting::get('start_year'), null, ['class' => 'form-control']) !!}
                
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Категория:</label>
            <div class="col-sm-4">
                
                {!! Form::select('categories_id', $categoriesArray, null,
                (count($categoriesArray) == 0 
                    ? array_merge(['placeholder' => 'Нет категорий', 'class' => 'form-control'], ['disabled' => '']) 
                    : array('class' => 'form-control')
                )) !!}
                
            </div>
            <label class="col-sm-2 control-label">Права:</label>
            <div class="col-sm-4">
                
                {!! Form::select('permission', [
                    'All'  => 'Всем',
                    'Url'  => 'По ссылке',
                    'Pass' => 'По паролю'
                ], null, array('class' => 'form-control', 'id' => 'album_permission')) !!}    
                
            </div>
        </div>

        <div class="collapse" id="collapse_pass">
            <div class="form-group">
                <div class="col-sm-3">
                    
                </div>
                <label class="col-sm-3 control-label text-danger">Пароль:</label>
                <div class="col-sm-4">
                    {!! Form::text('password', null, array('class' => 'form-control', 'id' => 'album_password')) !!}
                </div>
                <div class="col-sm-1">
                    <a class="btn btn-success btn-default" role="button" id="generate_password">
                       <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Описание:</label>
            <div class="col-sm-10">
                
                {!! Form::text('desc', null, array('class' => 'form-control')) !!}
                
            </div>
        </div>

        <div class="form-group">
            <center>
                <a class="btn btn-success btn-sm" role="button" data-toggle="collapse" 
                   href="#collapseAlbumForm" aria-expanded="false" aria-controls="collapseAlbumForm">
                  Продвинутые настройки (не обязательны)
                  <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                </a>
            </center>
        </div>

        <div class="collapse" id="collapseAlbumForm">
            <div class="form-group">
                <label class="col-sm-2 control-label">URL:</label>
                <div class="col-sm-4">
                    
                    {!! Form::text('url', null, array('class' => 'form-control', 'id' => 'album_url', 'required')) !!}
                    
                </div>

                <label class="col-sm-2 control-label">Директория:</label>
                <div class="col-sm-4">
                    
                    {!! Form::text('directory', null, array('class' => 'form-control', 'id' => 'album_directory', 'required')) !!}
                    
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Теги:</label>
                <div class="col-sm-10">
                    
                    {!! Form::text('tags', null, array('class' => 'form-control', 'id' => 'album_tags')) !!}
                    
                    <p class="help-block">Теги разделяются запятыми.</p>
                </div>
            </div>            
            
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-default" data-dismiss="modal">Отмена</button>

                {!! Form::submit('Создать альбом', 
                (count($categoriesArray) == 0 
                    ? array_merge(['class' => 'btn btn-primary'], ['disabled' => '']) 
                    : array('class' => 'btn btn-primary')
                )) !!}
                
        </div>

    {!! Form::close() !!}
