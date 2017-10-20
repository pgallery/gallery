@extends('default.layouts.app')

@section('content')

    <div class="page-header">
      <h2>{{ $thisAlbum->category()->name }} > {{ $thisAlbum->name }} > Фотографии <a href="" data-toggle="modal" data-target="#uploadsModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small></h2>
    </div>

{{ $listImages->links() }}

<table class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Миниатюра</th>
                <th>Имя</th>
                <th>Объем</th>
                <th>Разрешение</th>
                <th>Владелец</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Миниатюра</th>
                <th>Имя файла</th>
                <th>Объем</th>
                <th>Разрешение</th>
                <th>Владелец</th>
            </tr>

        </tfoot>
        <tbody>

    @foreach($listImages as $image)
        
            <tr>
                <td>{{ $image->id }}</td>
                <td> 
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary  btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('install-image', ['id' => $image->id]) }}"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Установить как миниатюру</a></li>
                        <li><a href="" data-toggle="modal" data-target="#RenameModal" class="clickRename" data-id="{{ $image->id }}" data-name="{{ $image->name }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Переименовать</a></li>
                        <li><a href="{{ route('rebuild-image', ['id' => $image->id]) }}"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Пересоздать миниатюру</a></li>
                        <li><a href="{{ route('rotate-image', ['option' => 'left', 'id' => $image->id]) }}"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Повернуть влево</a></li>
                        <li><a href="{{ route('rotate-image', ['option' => 'right', 'id' => $image->id]) }}"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> Повернуть вправо</a></li>
                        <li><a href="{{ route('rotate-image', ['option' => 'top', 'id' => $image->id]) }}"><span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span> Перевернуть</a></li>
                        
                        @if(Roles::is('admin'))
                        
                            <li><a href="" data-toggle="modal" data-target="#MoveToAlbumModal" class="clickMoveToAlbum" data-id="{{ $image->id }}" data-album="{{ $thisAlbum->id }}"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span> Переместить в альбом</a></li>
                            <li><a href="" data-toggle="modal" data-target="#ChangeOwnerModal" class="clickChangeOwner" data-id="{{ $image->id }}" data-owner="{{ $image->owner()->id }}"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Сменить владельца</a></li>
                        
                        @endif
                        
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-image', ['id' => $image->id]) }}" data-toggle="confirmation" data-title="Удалить фотографию?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                      </ul>
                    </div>
                    <a href="/{{ $thumbs_dir }}/{{ $thisAlbum->directory }}/{{ $image->name }}" data-fancybox="images"> 
                        <img  src="/{{ $thumbs_dir }}/{{ $thisAlbum->directory }}/{{ $image->name }}" width="75"/> 
                    </a>
                        @if($thisAlbum->images_id == $image->id) 
                            Миниатюра альбома 
                        @endif
                </td>
                <td><a href="/{{ $upload_dir }}/{{ $thisAlbum->directory }}/{{ $image->name }}" target="_blank">{{ $image->name }}</a>
                    <br><a href="/{{ $thumbs_dir }}/{{ $thisAlbum->directory }}/{{ $image->name }}" target="_blank">Миниатюра</a> 
                    | 
                    <a href="/{{ $mobile_dir }}/{{ $thisAlbum->directory }}/{{ $image->name }}" target="_blank">Мобильная</a> </td>
                <td>{{ \App\Helpers\Format::Bytes($image->size)  }}</td>
                <td>{{ $image->width }}х{{ $image->height }}</td>
                <td>{{ $image->owner()->name }}</td>
                
            </tr>

    @endforeach            
            
        </tbody>
    </table>


{{ $listImages->links() }}



<!-- Modal Uploads Images -->
<div class="modal fade" id="uploadsModal" tabindex="-1" role="dialog" aria-labelledby="uploadsModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="uploadsModalLabel">Загрузить изображения</h4>
      </div>
      
      <div class="modal-body">
                      
            @include('default.admin.upload.index')
            
      </div>

    </div>
  </div>
</div>

<!-- Modal Rename Image -->
<div class="modal fade" id="RenameModal" tabindex="-1" role="dialog" aria-labelledby="RenameModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="RenameModalLabel">Переименовывание изображения</h4>
      </div>
        
        {!! Form::open([
            'route'     => 'rename-image',
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

<!-- Modal Move To Album -->
<div class="modal fade" id="MoveToAlbumModal" tabindex="-1" role="dialog" aria-labelledby="MoveToAlbumModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="MoveToAlbumModal">Перемещение фотографии в другой альбом</h4>
      </div>
        
        {!! Form::open([
            'route'     => 'movetoalbum-image',
            'class'     => 'form-horizontal',
            'method'    => 'POST'
        ]) !!}        
          <input type="hidden"  name="id" id="MoveToAlbumID" value="">
          
      <div class="modal-body">
                      
            <div class="form-group">
                <label class="col-sm-2 control-label">Альбом:</label>
                <div class="col-sm-8">
                        {!! Form::select('MoveToAlbumNew', $albumsArray, null, array('class' => 'form-control', 'id' => 'MoveToAlbumNew')) !!}  
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

<!-- Modal Change Owner Image -->
<div class="modal fade" id="ChangeOwnerModal" tabindex="-1" role="dialog" aria-labelledby="ChangeOwnerModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ChangeOwnerModal">Смена владельца изображения</h4>
      </div>
        
        {!! Form::open([
            'route'     => 'changeowner-image',
            'class'     => 'form-horizontal',
            'method'    => 'POST'
        ]) !!}        
          <input type="hidden"  name="id" id="ChangeOwnerID" value="">
          
      <div class="modal-body">
                      
            <div class="form-group">
                <label class="col-sm-2 control-label">Владелец:</label>
                <div class="col-sm-8">
                        {!! Form::select('ChangeOwnerNew', $usersArray, null, array('class' => 'form-control', 'id' => 'ChangeOwnerNew')) !!}  
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

        $('a.clickMoveToAlbum').click(function(e){
            $('#MoveToAlbumID').val(this.getAttribute('data-id'));
            $('#MoveToAlbumNew').val(this.getAttribute('data-album'));
            e.preventDefault();
        });        
        
        $('a.clickChangeOwner').click(function(e){
            $('#ChangeOwnerID').val(this.getAttribute('data-id'));
            $('#ChangeOwnerNew').val(this.getAttribute('data-owner'));
            e.preventDefault();
        });

        $(":file").filestyle({
            input: false,
            buttonText: 'Выберите файлы'
        });
        
@endsection