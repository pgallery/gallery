@if(isset($type) && $type == 'edit')
    
    {!! Form::model($album, [
        'method'    => 'POST',
        'route'     => ['save-album', $album->id],
        'class'     => 'form-horizontal',
    ]) !!}     
    
@else

    {!! Form::open([
        'route'     => 'create-album',
        'class'     => 'form-horizontal',
        'method'    => 'POST'
    ]) !!}
        
@endif
        
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
                    'Url'  => 'По ссылке'
                ], null, array('class' => 'form-control')) !!}                
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
                <a class="btn btn-info btn-sm" 
                   role="button" 
                   data-toggle="collapse" 
                   href="#collapseAlbumForm" 
                   aria-expanded="false" 
                   aria-controls="collapseAlbumForm">
                  Дополнительные опции (не обязательны)
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
                    {!! Form::text('directory', null, 
                    ((!empty($album->directory) and !empty($type))
                        ? array_merge(['class' => 'form-control', 'required'], ['disabled' => '']) 
                        : array('class' => 'form-control', 'id' => 'album_directory', 'required')
                    )) !!}
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <center>
            @if(isset($type) && $type == 'edit')
                {!! Form::submit('Сохранить изменения', array('class' => 'btn btn-primary')) !!}
            @else
                {!! Form::submit('Создать альбом', 
                (count($categoriesArray) == 0 
                    ? array_merge(['class' => 'btn btn-primary'], ['disabled' => '']) 
                    : array('class' => 'btn btn-primary')
                )) !!}
            @endif
            </center>
        </div>

    {!! Form::close() !!}
