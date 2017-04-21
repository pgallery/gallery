@extends('template.header')

@section('content')


    <div class="page-header">
      <h2>Редактирование профиля </h2>
    </div>

<form class="form-horizontal" action="{{ route('save-profile') }}" method="POST">
        {!! csrf_field() !!}
        <div class="form-group">
            <label class="col-sm-4 control-label">Имя:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="name" value="{{ $user->name }}">
            </div>
        </div>    
        <div class="form-group">
            <label class="col-sm-4 control-label">E-Mail:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="email" value="{{ $user->email }}" @if($user->method != 'thisSite') disabled @endif>
            </div>
        </div> 

    @if( $user->method == 'thisSite')

        <div class="form-group">
            <label class="col-sm-4 control-label">Новый пароль:</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" name="newPassword" value="">
            </div>
        </div> 
        <div class="form-group">
            <label class="col-sm-4 control-label">Повторить пароль:</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" name="confirmPassword" value="">
            </div>
        </div>     
        <hr>
        <div class="form-group">
            <label class="col-sm-4 control-label">Текущий пароль:</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" name="password" value="">
            </div>
        </div>       
        <div class="form-group">
            <label class="col-sm-8 control-label">Для сохранения изменений необходимо указать действующий пароль</label>
        </div>        
        
    @endif        
        
    <center><button type="submit" class="btn btn-primary">Сохранить изменения</button></center>
</form>      

@endsection