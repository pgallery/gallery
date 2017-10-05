@extends('default.layouts.app')

@section('content')

    <div class="page-header">
      <h2>Редактирование пользователей </h2>
    </div>

{!! Form::open([
    'route'     => ['save-user', $user->id],
    'class'     => 'form-horizontal',
    'method'    => 'POST'
]) !!}

        <div class="form-group">
            <label class="col-sm-2 control-label">Имя пользователя:</label>
            <div class="col-sm-4">
                {!! Form::text('new_name', $user->name, array('class' => 'form-control', 'required')) !!}
            </div>
            <label class="col-sm-2 control-label">E-Mail:</label>
            <div class="col-sm-4">
                {!! Form::text('new_email', $user->email, array('class' => 'form-control', 'required')) !!}
            </div>
        </div>
    
        <div class="form-group">
            <label class="col-sm-2 control-label">Двухфакторная авторизация:</label>
            <div class="col-sm-4">
                {!! Form::checkbox('new_go2fa', 'yes', 
                (($user->google2fa_enabled)
                    ? true
                    : false
                )) !!}
            </div>
            <label class="col-sm-2 control-label">Новый пароль:</label>
            <div class="col-sm-4">
                {!! Form::password('new_password', ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">Права доступа:</label>
            <div class="col-sm-6">
                {!! Form::select('roles[]', $roles, $userRole, array('class' => 'form-control', 'multiple', 'required')) !!}
            </div>
        </div>
    <hr>
    <center>
        {!! Form::submit('Сохранить изменения', array('class' => 'btn btn-primary')) !!}
    </center>
    
{!! Form::close() !!}   

@endsection

