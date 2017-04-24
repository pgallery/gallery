@extends('template.header')

@section('content')

    <div class="page-header">
      <h2>Список пользователей </h2>
    </div>

<table id="users-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Имя</th>
                <th>E-Mail</th>
                <th>Метод авторизации</th>
                <th>Права</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Имя</th>
                <th>E-Mail</th>
                <th>Метод авторизации</th>
                <th>Права</th>
            </tr>

        </tfoot>
        <tbody>

    @foreach($users as $user)
        
            <tr>
                <td>{{ $user['id'] }}</td>

                <td>
                    @if($user['id'] != 1)
                    
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('edit-user', ['id' => $user['id']]) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-user', ['id' => $user['id']]) }}" data-toggle="confirmation" data-title="Удалить пользователя?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                      </ul>
                    </div>
                    
                    @endif
                    
                    {{ $user['name']  }} </td>
                <td>{{ $user['email'] }}</td>
                <td>
                    @if($user['method'] == 'thisSite')
                        Сайт
                    @else
                        {{ $user['method'] }}
                    @endif
                </td>
                <td>
                    @foreach($user->roles as $roles)
                       <span class="btn 
                               @if($roles->name == 'admin') 
                                    btn-danger
                               @elseif($roles->name == 'moderator') 
                                    btn-success
                               @elseif($roles->name == 'operator') 
                                    btn-info
                               @elseif($roles->name == 'viewer') 
                                    btn-warning
                               @else
                                    btn-default
                               @endif
                       ">{{ $roles->display_name }}</span>
                    @endforeach
                </td>
            </tr>

    @endforeach             
            
        </tbody>
</table>




@endsection

@section('js')

        $('#users-table').DataTable();

@endsection  