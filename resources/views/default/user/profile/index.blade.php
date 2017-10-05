@extends('default.layouts.app')

@section('content')


    <div class="page-header">
      <h2>Редактирование профиля </h2>
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

{!! Form::open([
    'route'     => 'save-profile',
    'class'     => 'form-horizontal',
    'method'    => 'POST'
]) !!}

        <div class="form-group">
            <label class="col-sm-4 control-label">Имя:</label>
            <div class="col-sm-4">
                {!! Form::text('name', $user->name, array('class' => 'form-control', 'required')) !!}
            </div>
        </div>    
        <div class="form-group">
            <label class="col-sm-4 control-label">E-Mail:</label>
            <div class="col-sm-4">
                {!! Form::email('email', $user->email, 
                ($user->method != 'thisSite'
                    ? array_merge(['class' => 'form-control'], ['disabled' => '']) 
                    : array('class' => 'form-control')
                )) !!}
            </div>
        </div> 

        <div class="form-group">
            <label class="col-sm-4 control-label">Включить 2-х факторную авторизацию:</label>
            <div class="col-sm-4">
                @if($user->google2fa_enabled)

                    <a href="{{ route('disabled2fa-profile') }}">Выкл</a>
                
                @else
                
                    <a href="{{ route('enable2fa-profile') }}">Вкл</a>
                
                @endif
            </div>
        </div>

    @if( $user->method == 'thisSite')
        <div class="form-group">
            <label class="col-sm-4 control-label">Новый пароль:</label>
            <div class="col-sm-4">
                {!! Form::password('newPassword', ['class' => 'form-control']) !!}
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-4 control-label">Повторить пароль:</label>
            <div class="col-sm-4">
                {!! Form::password('confirmPassword', ['class' => 'form-control']) !!}
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label class="col-sm-4 control-label">Текущий пароль:</label>
            <div class="col-sm-4">
                {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-8 control-label">Для сохранения изменений необходимо указать действующий пароль</label>
        </div>
    @endif
        
    <center>
        {!! Form::submit('Сохранить изменения', array('class' => 'btn btn-primary')) !!}
    </center>
    
{!! Form::close() !!} 



@endsection