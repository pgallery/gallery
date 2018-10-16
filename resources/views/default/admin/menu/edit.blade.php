@extends('default.layouts.app')

@section('content')

    <div class="page-header">
      <h2>Редактирование меню "{{ $menu->name }}"</h2>
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

    {!! Form::model($menu, [
        'method'    => 'POST',
        'route'     => 'save-menu',
        'class'     => 'form-horizontal',
    ]) !!}

    {!! Form::hidden('id', $menu->id) !!}

        <div class="form-group">
            <label class="col-sm-2 control-label">Название меню:</label>
            <div class="col-sm-5">
                {!! Form::text('name', null, array('class' => 'form-control', 'required')) !!}
            </div>
            <label class="col-sm-2 control-label">Отображение:</label>
            <div class="col-sm-1">
                {!! Form::checkbox('show', 'yes', 
                (($menu->show == 'Y')
                    ? true
                    : false
                )) !!}
            </div>
            <label class="col-sm-1 control-label">Порядок:</label>
            <div class="col-sm-1">
                {!! Form::text('sort', null, array('class' => 'form-control', 'required')) !!}
            </div>
        </div>
    
        <div class="form-group">
            <label class="col-sm-4 control-label">Список тегов:</label>
            <div class="col-sm-6">
                {!! Form::select('tags[]', $tags, $menuTags, array('class' => 'form-control', 'multiple', 'required')) !!}
            </div>
        </div>

    
    <hr>
    <center>
        {!! Form::submit('Сохранить изменения', ['class' => 'btn btn-primary']) !!}
    </center>
    
    {!! Form::close() !!}     
    
@endsection