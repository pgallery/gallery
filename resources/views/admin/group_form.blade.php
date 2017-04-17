@if(isset($type) && $type == 'edit')
    <form class="form-horizontal" action="{{ route('save-group', ['id' => $groupId]) }}" method="POST">
        
@else
    <form class="form-horizontal" action="{{ route('create-group') }}" method="POST">

@endif
        
        <div class="row">
          <div class="col-xs-6">
            Название группы:
            <input type="text" class="form-control" name="groupName" @isset($groupName) value="{{ $groupName }}" @endisset>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Создать</button>
        
    </form> 
