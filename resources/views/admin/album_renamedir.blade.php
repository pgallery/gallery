@extends('template.header')

@section('content')
    <div class="page-header">
      <h2>{{ $thisAlbum->group()->name }} > {{ $thisAlbum->name }} > Переименование директории</h2>
    </div>


{!! Form::open([
    'route'       => ['savedir-album', $thisAlbum->id],
    'class'       => 'form-horizontal',
    'method'      => 'POST'
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
                {{ $thisAlbum->name }}
            </div>
            <label class="col-sm-2 control-label">URL:</label>
            <div class="col-sm-4">
                {{ $thisAlbum->url }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Директория:</label>
            <div class="col-sm-4">
                {{ $thisAlbum->directory }}
            </div>
            <label class="col-sm-2 control-label">Год:</label>
            <div class="col-sm-4">
                {{ $thisAlbum->year }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Группа:</label>
            <div class="col-sm-4">
                {{ $thisAlbum->group()->name }}
            </div>
            <label class="col-sm-2 control-label">Права:</label>
            <div class="col-sm-4">
                {{ $thisAlbum->permission }}              
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Описание:</label>
            <div class="col-sm-10">
                {{ $thisAlbum->desc }}
            </div>
        </div>
<hr>
<div class="form-group">
    <label class="col-sm-4 control-label">Имя директории:</label>
    <div class="col-sm-6">
        {!! Form::text('directory', $thisAlbum->directory, array('class' => 'form-control', 'required')) !!}
    </div>
</div>
<center>
    {!! Form::submit('Сохранить', array('class' => 'btn btn-primary')) !!}       
</center>

{!! Form::close() !!}

@endsection


