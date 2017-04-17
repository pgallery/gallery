@extends('template.header')

@section('content')
<h3>Группы</h3>
<table id="group-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Название</th>
                <th>Всего альбомов</th>
                <th>Публичных альбомов</th>
                <th>Закрытых альбомов</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Название</th>
                <th>Всего альбомов</th>
                <th>Публичных альбомов</th>
                <th>Закрытых альбомов</th>
            </tr>

        </tfoot>
        <tbody>
    @foreach($groups as $group)
        
            <tr>
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


<h3>Альбомы</h3>
<table id="album-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
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
                <td> 
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary  btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('edit-album', ['id' => $album['id']]) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a></li>
                        <li><a href="{{ route('show-album', ['id' => $album['id']]) }}"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> Просмотреть фотографии</a></li>
                        <li><a href="{{ route('uploads-album', ['id' => $album['id']]) }}"><span class="glyphicon glyphicon-paste" aria-hidden="true"></span> Загрузить фотографии</a></li>
                        <li><a href="{{ route('sync-album', ['id' => $album['id']]) }}"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Синхронизировать из директории</a></li>
                        <li><a href="/"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Пересоздать все миниатюры</a></li>
                        
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-album', ['id' => $album['id']]) }}" data-toggle="confirmation" data-title="Удалить альбом и все фотографии?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                      </ul>
                    </div>                    
                    
                     {{ $album['name'] }}
                </td>
                <td>
                    <a href="{{ $album['url'] }}" target="_blank">{{ $album['url'] }}</a>
                </td>
                <td>
                    @if(!empty($album['thumbs_url']))
                        <a href="{{ $album['thumbs_url'] }}" data-fancybox="images"> 
                            <img  src="{{ $album['thumbs_url'] }}" width="75"/> 
                        </a>
                    @endif                    
                </td>
                <td>{{ $album['count'] }}</td>
                <td>{{ $album['summary_size'] }}</td>
                <td>{{ $album['groups_name'] }}</td>
                <td>{{ $album['year'] }}</td>
                <td>{{ $album['permission'] }}</td>
                <td>{{ $album['owner'] }}</td>
            </tr>

    @endforeach
            
        </tbody>
    </table>





@endsection
