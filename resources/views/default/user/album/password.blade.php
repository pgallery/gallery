@extends('default.layouts.app')

@section('content')

<div class="page-header">
  <h2>Альбом защищен паролем</h2>
</div>

<div class="well">
    <p><b>Внимание!</b> Для доступа к данному альбому Вам необходимо указать пароль.</p>
</div>

    {!! Form::open([
        'route'     => ['auth-album', 'url' => $url],
        'class'     => 'form-horizontal',
        'method'    => 'POST'
    ]) !!}

    <div class="form-group">
        <div class="col-sm-3"></div>
            <div class="col-sm-3">
            {!! Form::password('password', array('class' => 'form-control', 'required')) !!}
            </div>
            <div class="col-sm-3">
            {!! Form::submit('Авторизоваться', array('class' => 'btn btn-primary')) !!}     
            </div>
        <div class="col-sm-3"></div>
    </div>
    
    {!! Form::close() !!}
@endsection