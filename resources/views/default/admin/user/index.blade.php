@extends('default.layouts.app')

@section('content')

    <div class="page-header">
      <h2>Список пользователей <a href="" data-toggle="modal" data-target="#newUserModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small></h2>
    </div>

<table id="users-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Имя</th>
                <th>E-Mail</th>
                <th>Категорий</th>
                <th>Альбомов</th>
                <th>Фотографий</th>
                <th>Метод авторизации</th>
                <th>2FA</th>
                <th>Права</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Имя</th>
                <th>E-Mail</th>
                <th>Категорий</th>
                <th>Альбомов</th>
                <th>Фотографий</th>                
                <th>Метод авторизации</th>
                <th>2FA</th>
                <th>Права</th>
            </tr>

        </tfoot>
        <tbody>

    @foreach($users as $user)
        
            <tr>
                <td>{{ $user->id }}</td>

                <td>
                    @if($user->id != 1)
                    
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('edit-user', ['id' => $user->id]) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-user', ['id' => $user->id]) }}" data-toggle="confirmation" data-title="Удалить пользователя?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                      </ul>
                    </div>
                    
                    @endif
                    
                    {{ $user->name  }} </td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->categoriesCount() }}</td>
                <td>{{ $user->albumsCount() }}</td>
                <td>{{ $user->imagesCount() }}</td>
                <td>
                    @if($user->method == 'thisSite')
                        Сайт
                    @else
                        {{ $user->method }}
                    @endif
                </td>
                <td>
                    @if($user->google2fa_enabled)
                        Вкл
                    @else
                        Выкл
                    @endif
                </td>
                <td><h4>
                    @foreach($user->roles as $roles)
                    
                       <label class="label  
                               @if($roles->name == 'admin') 
                                    label-danger
                               @elseif($roles->name == 'moderator') 
                                    label-success
                               @elseif($roles->name == 'operator') 
                                    label-info
                               @elseif($roles->name == 'viewer') 
                                    label-warning
                               @else
                                    label-default
                               @endif
                       ">{{ $roles->display_name }}</label>
                    @endforeach
                </h4></td>
            </tr>

    @endforeach             
            
        </tbody>
</table>

<!-- Modal add User -->
<div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="newUserModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newUserModalLabel">Добавление пользователя</h4>
      </div>
        
        {!! Form::open([
            'route'     => 'create-users',
            'class'     => 'form-horizontal',
            'method'    => 'POST'
        ]) !!}
        
      <div class="modal-body">
          
         
        <div class="form-group">
            <label class="col-sm-2 control-label">Имя:</label>
            <div class="col-sm-4">
                {!! Form::text('name', null, array('class' => 'form-control', 'required')) !!}
            </div>
            <label class="col-sm-2 control-label">E-Mail:</label>
            <div class="col-sm-4">
                {!! Form::email('email', null, array('class' => 'form-control', 'required')) !!}
            </div>
        </div>
          
        <div class="form-group">
            <label class="col-sm-2 control-label">Пароль:</label>
            <div class="col-sm-4">
                {!! Form::password('password', array('class' => 'form-control', 'required')) !!}
            </div>
            <label class="col-sm-2 control-label">Повторить:</label>
            <div class="col-sm-4">
                {!! Form::password('password_confirmation', array('class' => 'form-control', 'required')) !!}
            </div>
        </div>    
          
        <div class="form-group">
            <label class="col-sm-4 control-label">Права:</label>
            <div class="col-sm-6">
                {!! Form::select('roles[]', $allRoles, null, array('class' => 'form-control', 'multiple', 'required')) !!}
            </div>
        </div>
          
          
        <center>
            {!! Form::submit('Добавить пользователя', array('class' => 'btn btn-primary')) !!}
        </center>
            
      </div>
          
        {!! Form::close() !!}
        
    </div>
  </div>
</div>


@endsection

@section('js')

        $('#users-table').DataTable({
          "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"] ],
          "language": {
            "search": "Фильтр:",
            "zeroRecords": "Нет данных для отображения",
            "sLengthMenu": "Отображать _MENU_ записей",
            "info": "Показаны записи _START_ - _END_, всего _TOTAL_ записей",
            "paginate": {
              "previous": "Назад",
              "next": "Дальше"
            }              
          }
        });

@endsection  