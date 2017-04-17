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
                        <li><a href="{{ route('restoreGroup-trash', ['option' => 'group', 'id' => $group['id']]) }}"><span class="glyphicon glyphicon-paste" aria-hidden="true"></span> Восстановить</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-group', ['id' => $group['id']]) }}" data-toggle="confirmation" data-title="Удалить группу, а так же все альбомы и все фотографии?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить безвозвратно</a></li>
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

@endsection
