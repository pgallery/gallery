@extends('default.layouts.app')

@section('content')

    <div class="page-header">
      <h2>Редактирование альбома "{{ $album->name }}"</h2>
    </div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    {!! Form::model($album, [
        'method'    => 'POST',
        'route'     => 'save-album',
        'class'     => 'form-horizontal',
    ]) !!} 


    {!! Form::hidden('id', $album->id) !!}
    
<ul class="nav nav-tabs">
  
    <li class="active">
        <a data-toggle="tab" data-toggle="tab" href="#base">
            Основное
        </a>
    </li>
    <li>
        <a data-toggle="tab" href="#additional">
            Дополнительное
        </a>
    </li>
    <li>
        <a data-toggle="tab" href="#owner">
            Владелец
        </a>
    </li>    
    
</ul>

<div class="tab-content">
    <div id="base" class="tab-pane fade in active">
        <div class="page-header">
            <h6>Основные данные альбома</h6>
        </div>    
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
                <div class="col-sm-6">
                    
                </div>
                <label class="col-sm-3 control-label text-danger">Пароль альбома:</label>
                <div class="col-sm-2">
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
    </div>
    <div id="additional" class="tab-pane fade">
        <div class="page-header">
            <h6>Дополнительные данные альбома</h6>
        </div>  
            <div class="form-group">
                <label class="col-sm-2 control-label">URL:</label>
                <div class="col-sm-4">
                    
                    {!! Form::text('url', null, array('class' => 'form-control', 'id' => 'album_url', 'required')) !!}
                    
                </div>

                <label class="col-sm-2 control-label">Директория:</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-warning-sign" 
                            aria-hidden="true" 
                            data-toggle="tooltip" 
                            data-placement="left" title="Изменение директории повлечет за собой перемещение фотографий и пересоздание их миниатюр."></span></span>
                        {!! Form::text('directory', null, array('class' => 'form-control', 'id' => 'album_directory', 'required')) !!}
                    </div>
                </div>
                
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Теги:</label>
                <div class="col-sm-10">

                    {!! Form::text('tags', $tags, array('class' => 'form-control', 'id' => 'album_tags')) !!}
                    
                    <p class="help-block">Теги разделяются запятыми.</p>
                </div>
            </div>            

    </div>
    
    <div id="owner" class="tab-pane fade">
        <div class="page-header">
            <h6>Владелец альбома</h6>
        </div>  
            <div class="form-group">
                <label class="col-sm-2 control-label">Владелец:</label>
                <div class="col-sm-8">
                        {!! Form::select('users_id', $usersArray, null, array('class' => 'form-control', 'id' => 'owner')) !!}  
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Рекурсивно:</label>
                <div class="col-sm-8">
                        {!! Form::checkbox('owner_recursion', 'yes') !!}  
                </div>
            </div>
    </div>    
    
    
</div>

    <hr>
    <center>
        {!! Form::submit('Сохранить изменения', 
        (count($categoriesArray) == 0 
            ? array_merge(['class' => 'btn btn-primary'], ['disabled' => '']) 
            : array('class' => 'btn btn-primary')
        )) !!}
    </center>
    
    {!! Form::close() !!}      

@endsection

@section('js-top')

    function random_pass() {
            var result       = '';
            var words        = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
            var max_position = words.length - 1;
                    for( i = 0; i < 12; ++i ) {
                            position = Math.floor ( Math.random() * max_position );
                            result = result + words.substring(position, position + 1);
                    }
            return result;
    }
    
    $('#generate_password').click(function() {
        $('#album_password').attr('value', random_pass());
    });
    
    $("#album_permission").change(function(){
        if ($(this).val()=="Pass" ){
            console.log('show');
            $('#collapse_pass').collapse('show');
        }else{
            console.log('hide');
            $('#collapse_pass').collapse('hide');
        }
    }).change();

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
    
@endsection