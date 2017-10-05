@extends('default.layouts.app')

@section('content')

    <div class="page-header">
      <h2>Удаление пользователя "{{ $user->name }}"</h2>
    </div>

<div class="alert alert-danger" role="alert">
    <b>
    ВНИМАНИЕ! При удалении пользователя "{{ $user->name }}" были обнаружены связанные объекты:
    <br>
    @if($user->groupsCount() != 0)
        <br>Группы: {{ $user->groupsCount() }}
    @endif
    
    @if($user->albumsCount() != 0)
        <br>Альбомы: {{ $user->albumsCount() }}
    @endif    
    
    @if($user->imagesCount() != 0)
        <br>Изображения: {{ $user->imagesCount() }}
    @endif    
    
    </b>
</div>


        <div class="form-group">
            <label class="col-sm-6 control-label">Удалить пользователя вместе со всеми связанными объектами:</label>
            <label class="col-sm-6 control-label">Передать все объекты другому пользователю, а затем продолжить удаление:</label>            
        </div> 

        <div class="form-group">
            <div class="col-sm-6">
                
                {!! Form::open([
                    'route'     => ['force-delete-user', $user->id],
                    'class'     => 'form-horizontal',
                    'method'    => 'POST'
                ]) !!}
                <center>
                    {!! Form::submit('Да, удалить', array('class' => 'btn btn-danger')) !!}
                </center>
                {!! Form::close() !!} 

    
            </div>
            
            <div class="col-sm-4">
                {!! Form::open([
                    'route'     => ['migrate-edelete-user', $user->id],
                    'class'     => 'form-horizontal',
                    'method'    => 'POST'
                ]) !!}
                
                {!! Form::select('newOwner', $allUsers, null, array('class' => 'form-control', 'required')) !!}
                <br>
                <center>
                    {!! Form::submit('Передать и продолжить', array('class' => 'btn btn-danger')) !!}
                </center>
                {!! Form::close() !!}
            </div>
        </div>

@endsection
