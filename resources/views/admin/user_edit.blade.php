@extends('template.header')

@section('content')

    <div class="page-header">
      <h2>Редкатирование пользователей </h2>
    </div>

<form class="form-horizontal" action="" method="POST">

    <input type="hidden" name="userId" value="{{ $user->id }}">
        <div class="form-group">
            <label class="col-sm-2 control-label">Имя пользователя:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="userName" value="{{ $user->name }}">
            </div>
            <label class="col-sm-2 control-label">E-Mail:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="userEMail" value="{{ $user->email }}">
            </div>
        </div>    
    
        <div class="form-group">
            <label class="col-sm-4 control-label">Права доступа:</label>
            <div class="col-sm-6">
                
                <select multiple class="form-control" name="userRoles">
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->display_name }} ({{ $role->description }})</option>
                    @endforeach
                </select>
                
            </div>
        </div>
    
        <div class="form-group">
            <label class="col-sm-4 control-label">текущие права:</label>
            <div class="col-sm-6">
                

                    @foreach($user->roles as $role)
                    ID {{ $role->id }}, {{ $role->display_name }} ({{ $role->description }}) <br>
                    @endforeach

                
            </div>
        </div>    
    
    <hr>
    <center><button type="submit" class="btn btn-primary">Сохранить изменения</button></center>
</form>    

@endsection

