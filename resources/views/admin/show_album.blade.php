@extends('template.header')

@section('content')

<h3>{{ $album_name }} > Фотографии</h3>
{{ $album_images->links() }}
<table class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Миниатюра</th>
                <th>Имя</th>
                <th>Объем</th>
                <th>Владелец</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Миниатюра</th>
                <th>Имя файла</th>
                <th>Объем</th>
                <th>Владелец</th>
            </tr>

        </tfoot>
        <tbody>

    @foreach($images as $image)
        
            <tr>
                <td>{{ $image['id'] }}</td>
                <td> 
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary  btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('install-image', ['id' => $image['id']]) }}"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Установить как миниатюру</a></li>
                        <li><a href="{{ route('rebuild-image', ['id' => $image['id']]) }}"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Пересоздать миниатюру</a></li>
                        <li><a href="{{ route('rotate-image', ['option' => 'left', 'id' => $image['id']]) }}"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Повернуть влево</a></li>
                        <li><a href="{{ route('rotate-image', ['option' => 'right', 'id' => $image['id']]) }}"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> Повернуть вправо</a></li>
                        <li><a href="{{ route('rotate-image', ['option' => 'top', 'id' => $image['id']]) }}"><span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span> Перевернуть</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-image', ['id' => $image['id']]) }}" data-toggle="confirmation" data-title="Удалить фотографию?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                      </ul>
                    </div>
                    <a href="{{ $image['thumbs_url'] }}" data-fancybox="images"> 
                        <img  src="{{ $image['thumbs_url'] }}" width="75"/> 
                    </a>
                        @if($album_preview == $image['id']) 
                            Миниатюра альбома 
                        @endif
                </td>
                <td><a href="{{ $image['image_url'] }}" target="_blank">{{ $image['name'] }}</a>
                    <br><a href="{{ $image['thumbs_url'] }}" target="_blank">Миниатюра</a> <a href="{{ $image['mobile_url'] }}" target="_blank">Мобильная</a> </td>
                <td>{{ $image['size'] }}</td>
                <td>{{ $image['owner'] }}</td>
                
            </tr>

    @endforeach            
            
        </tbody>
    </table>


{{ $album_images->links() }}


@endsection
