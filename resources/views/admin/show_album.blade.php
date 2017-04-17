@extends('template.header')

@section('content')

<h3>{{ $album_name }} > Фотографии</h3>
<table id="album-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Миниатюра</th>
                <th>Имя</th>
                <th>Объем</th>
                <th>Владелец</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Миниатюра</th>
                <th>Имя файла</th>
                <th>Объем</th>
                <th>Владелец</th>
            </tr>

        </tfoot>
        <tbody>

    @foreach($images as $image)
        
            <tr>
                <td> 
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary  btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('install-image', ['id' => $image['id']]) }}"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Установить как миниатюру</a></li>
                        <li><a href="/"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Пересоздать миниатюру</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-image', ['id' => $image['id']]) }}" data-toggle="confirmation" data-title="Удалить альбом и все фотографии?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                      </ul>
                    </div>
                    <a href="{{ $image['thumbs_url'] }}" data-fancybox="images"> 
                        <img  src="{{ $image['thumbs_url'] }}" width="75"/> 
                    </a>
                        @if($album_preview == $image['id']) 
                            Миниатюра альбома 
                        @endif
                </td>
                <td>{{ $image['name'] }}</td>
                <td>{{ $image['size'] }}</td>
                <td>{{ $image['owner'] }}</td>
                
            </tr>

    @endforeach            
            
        </tbody>
    </table>





@endsection
