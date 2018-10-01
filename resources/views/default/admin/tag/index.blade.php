@extends('default.layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="page-header">
      <h2>Теги </h2>
    </div>

<table id="tag-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>id</th>
            <th>Тег</th>
            <th>Кол-во</th>
            <th>Альбомы</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>id</th>
            <th>Тег</th>
            <th>Кол-во</th>
            <th>Альбомы</th>
        </tr>

    </tfoot>
    <tbody>
@foreach($tags as $tag)

        <tr>
            <td>{{ $tag->id }}</td>
            <td> 
                <!-- Single button -->
                <div class="btn-group">
                  <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    
                    <li><a href="" data-toggle="modal" data-target="#RenameModal" class="clickRename" data-id="{{ $tag->id }}" data-name="{{ $tag->name }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Переименовать</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('delete-tag', ['id' => $tag->id]) }}" data-toggle="confirmation" data-title="Удалить данный тег?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                  </ul>
                </div>                    

                 {{ $tag->name }}

            </td>
            <td>
                
                {{ $tag->albumsCount() }}
                
            </td>
            <td>
                @foreach($tag->albums as $album)
                    
                    <a href="#" class="btn btn-default btn-xs disabled" role="button">{{ $album->name }}</a>
                
                @endforeach
            </td>

        </tr>

@endforeach

    </tbody>
</table>

<!-- Modal Rename Tag -->
<div class="modal fade" id="RenameModal" tabindex="-1" role="dialog" aria-labelledby="RenameModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="RenameModalLabel">Переименовывание тега</h4>
      </div>
        
        {!! Form::open([
            'route'     => 'rename-tag',
            'class'     => 'form-horizontal',
            'method'    => 'POST'
        ]) !!}        
          <input type="hidden"  name="id" id="id" value="">
          
      <div class="modal-body">
                      
            <div class="form-group">
                <label class="col-sm-2 control-label">Имя:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="newName" id="newName" value="">
                </div>
            </div>              
            
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" data-dismiss="modal">Отмена</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
      </div>
          
      {!! Form::close() !!}
          
    </div>
  </div>
</div>

@endsection

@section('js-top')

        $('a.clickRename').click(function(e){
            $('#id').val(this.getAttribute('data-id'));
            $('#newName').val(this.getAttribute('data-name'));
            e.preventDefault();
        });

@endsection        