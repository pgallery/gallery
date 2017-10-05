@extends('default.layouts.app')

@section('content')

    <div class="page-header">
      <h2>Отключение двухфакторной авторизации </h2>
    </div>

{!! Form::open([
    'route'     => 'disabled2fa-profile',
    'class'     => 'form-horizontal',
    'method'    => 'POST'
]) !!}

    <center>
        {!! Form::submit('Отключить', array('class' => 'btn btn-primary')) !!}
    </center>
{!! Form::close() !!} 

@endsection