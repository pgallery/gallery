@extends('template.header')

@section('content')
<h3>Группы <small><a href="" data-toggle="modal" data-target="#newGroupModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small></h3> 
<table id="group-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Название</th>
                <th>Всего альбомов</th>
                <th>Публичных альбомов</th>
                <th>Закрытых альбомов</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Название</th>
                <th>Всего альбомов</th>
                <th>Публичных альбомов</th>
                <th>Закрытых альбомов</th>
            </tr>

        </tfoot>
        <tbody>
    @foreach($groups as $group)
        
            <tr>
                <td>{{ $group['id'] }}</td>
                <td> 
                    @if(Helper::isAdmin(Auth::user()->id))
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary  btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('edit-group', ['id' => $group['id']]) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-group', ['id' => $group['id']]) }}" data-toggle="confirmation" data-title="Удалить группу, а так же все альбомы и все фотографии?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                      </ul>
                    </div>                    
                    @endif
                     {{ $group['name'] }}
                </td>

                <td>{{ $group->albumCount() }}</td>
                <td>{{ $group->albumCountPublic() }}</td>
                <td>{{ $group->albumCountPrivate() }}</td>
            </tr>

    @endforeach
            
        </tbody>
    </table>


<h3>Альбомы <small><a href="" data-toggle="modal" data-target="#newAlbumModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small></h3> 



<table id="album-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Название</th>
                <th>URL</th>
                <th>Миниатюра</th>
                <th>Фотографий</th>
                <th>Объем</th>
                <th>Группа</th>
                <th>Год</th>
                <th>Доступ</th>
                <th>Владелец</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Название</th>
                <th>URL</th>
                <th>Миниатюра</th>
                <th>Фотографий</th>
                <th>Объем</th>
                <th>Группа</th>
                <th>Год</th>
                <th>Доступ</th>
                <th>Владелец</th>
            </tr>

        </tfoot>
        <tbody>
    @foreach($albums as $album)
        
            <tr
            @if(empty($album['thumbs_url']))
                 class="danger"
            @endif
            >
                <td>{{ $album['id'] }}</td>
                <td> 
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('edit-album', ['id' => $album['id']]) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a></li>
                        @if($album['count'] != 0)
                            <li><a href="{{ route('show-album', ['id' => $album['id']]) }}"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> Просмотреть фотографии</a></li>
                            <li><a href="{{ route('rebuild-album', ['id' => $album['id']]) }}"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Пересоздать все миниатюры</a></li>
                        @endif
                        <li><a href="{{ route('uploads-album', ['id' => $album['id']]) }}"><span class="glyphicon glyphicon-paste" aria-hidden="true"></span> Загрузить фотографии</a></li>
                        <li><a href="{{ route('sync-album', ['id' => $album['id']]) }}"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Синхронизировать из директории</a></li>
                        
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-album', ['id' => $album['id']]) }}" data-toggle="confirmation" data-title="Удалить альбом и все фотографии?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                      </ul>
                    </div>                    
                    
                     {{ $album['name'] }}
                </td>
                <td>
                    
                    <a href="{{ env('APP_URL') }}/gallery-{{ $album['url'] }}" class="btn btn-default btn-xs" target="_blank">
                        <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>
                    </a>
                    
                    
                </td>
                <td>
                    @if(!empty($album['thumbs_url']))
                        <a href="{{ $album['thumbs_url'] }}" data-fancybox="images"> 
                            <img  src="{{ $album['thumbs_url'] }}" width="75"/> 
                        </a>
                    @endif
                </td>
                <td>{{ $album['count'] }}</td>
                <td>{{ round(($album['summary_size'] / 1024 / 1024)) }} Mb</td>
                <td>{{ $album['groups_name'] }}</td>
                <td>{{ $album['year'] }}</td>
                <td>{{ $album['permission'] }}</td>
                <td>{{ $album['owner'] }}</td>
            </tr>

    @endforeach
            
        </tbody>
    </table>



<!-- Modal add Group -->
<div class="modal fade" id="newGroupModal" tabindex="-1" role="dialog" aria-labelledby="newGroupModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newGroupModalLabel">Добавление группы</h4>
      </div>
        

      <div class="modal-body">
          
         @include('admin.group_form')
            
      </div>
          
    </div>
  </div>
</div>

<!-- Modal add Group -->
<div class="modal fade" id="newAlbumModal" tabindex="-1" role="dialog" aria-labelledby="newAlbumModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newAlbumModalLabel">Добавление альбома</h4>
      </div>
        

      <div class="modal-body">
          
         @include('admin.album_form')
            
      </div>
          
    </div>
  </div>
</div>

@endsection
