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

                <td>{{ $user['name']  }}</td>
                <td>{{ $user['email'] }}</td>
                <td>{{ $user['method'] }}</td>
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