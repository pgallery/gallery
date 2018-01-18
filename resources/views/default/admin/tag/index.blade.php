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

<h3>Теги </h3>

<table id="tag-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>id</th>
            <th>Тег</th>
            <th>Альбомы</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>id</th>
            <th>Тег</th>
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
                    <li><a href="#"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a></li>

                    <li role="separator" class="divider"></li>
                    <li><a href="#" data-toggle="confirmation" data-title="Удалить альбом и все фотографии?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                  </ul>
                </div>                    

                 {{ $tag->name }}

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

@endsection