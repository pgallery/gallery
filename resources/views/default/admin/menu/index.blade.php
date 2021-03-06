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
      <h2>Меню <a href="" data-toggle="modal" data-target="#newMenuModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small></h2>
    </div>

<table id="tag-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>id</th>
            <th>Меню</th>
            <th>Теги</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>id</th>
            <th>Меню</th>
            <th>Теги</th>
        </tr>

    </tfoot>
    <tbody>
        
@foreach($menus as $menu)
        
        <tr>
            <td>{{ $menu->id }}</td>
            <td> 
                <!-- Single button -->
                <div class="btn-group">
                  <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    
                    <li><a href="" data-toggle="modal" data-target="#RenameModal" class="clickRename" data-id="{{ $menu->id }}" data-name="{{ $menu->name }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Переименовать</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('delete-menu', ['id' => $menu->id]) }}" data-toggle="confirmation" data-title="Удалить данное меню?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                  </ul>
                </div>

                 {{ $menu->name }}

            </td>
            <td>
                @foreach($menu->tagsRelation() as $tags)
                    
                    <a href="#" class="btn btn-default btn-xs disabled" role="button">{{ $tags->name }}</a>
                
                @endforeach
            </td>

        </tr>        

@endforeach

    </tbody>
</table>   

<!-- Modal Rename Menu -->
<div class="modal fade" id="RenameModal" tabindex="-1" role="dialog" aria-labelledby="RenameModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="RenameModalLabel">Переименовывание меню</h4>
      </div>
        
        {!! Form::open([
            'route'     => 'rename-menu',
            'class'     => 'form-horizontal',
            'method'    => 'POST'
        ]) !!}        
          <input type="hidden"  name="id" id="id" value="">
          
      <div class="modal-body">
                      
            <div class="form-group">
                <label class="col-sm-2 control-label">Название:</label>
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

<!-- Modal add Menu -->
<div class="modal fade" id="newMenuModal" tabindex="-1" role="dialog" aria-labelledby="newMenuModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newMenuModalLabel">Добавление меню</h4>
      </div>
        
        {!! Form::open([
            'route'     => 'create-menu',
            'class'     => 'form-horizontal',
            'method'    => 'POST'
        ]) !!}
        
      <div class="modal-body">
          
         
        <div class="form-group">
            <label class="col-sm-2 control-label">Название:</label>
            <div class="col-sm-4">
                {!! Form::text('name', null, array('class' => 'form-control', 'required')) !!}
            </div>
        </div>
          
  
          
        <div class="form-group">
            <label class="col-sm-4 control-label">Теги:</label>
            <div class="col-sm-6">
                {!! Form::select('tags[]', $allTags, null, array('class' => 'form-control', 'multiple', 'required')) !!}
            </div>
        </div>
          
          
        <center>
            {!! Form::submit('Добавить меню', array('class' => 'btn btn-primary')) !!}
        </center>
            
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