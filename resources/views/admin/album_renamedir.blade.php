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


