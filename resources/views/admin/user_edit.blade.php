@extends('template.header')

@section('content')

    <div class="page-header">
      <h2>Редкатирование пользователей </h2>
    </div>

{!! Form::open([
    'route'     => ['save-user', $user->id],
    'class'     => 'form-horizontal',
    'method'    => 'POST'
]) !!}

        <div class="form-group">
            <label class="col-sm-2 control-label">Имя пользователя:</label>
            <div class="col-sm-4">
                {!! Form::text('name', $user->name, array('class' => 'form-control')) !!}
            </div>
            <label class="col-sm-2 control-label">E-Mail:</label>
            <div class="col-sm-4">
                {!! Form::text('email', $user->email, array('class' => 'form-control')) !!}
            </div>
        </div>
    
        <div class="form-group">
            <label class="col-sm-4 control-label">Права доступа:</label>
            <div class="col-sm-6">
                {!! Form::select('roles[]', $roles, $userRole, array('class' => 'form-control','multiple')) !!}
            </div>
        </div>
    <hr>
    <center>
        {!! Form::submit('Сохранить изменения', array('class' => 'btn btn-primary')) !!}
    </center>
    
{!! Form::close() !!}   

@endsection

