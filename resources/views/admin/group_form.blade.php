@if(isset($type) && $type == 'edit')
    <form class="form-horizontal" action="{{ route('save-group', ['id' => $groupId]) }}" method="POST">
        
@else
    <form class="form-horizontal" action="{{ route('create-group') }}" method="POST">

@endif
        
        <div class="form-group">
            <label class="col-sm-4 control-label">Название группы:</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="groupName" @isset($groupName) value="{{ $groupName }}" @endisset>
            </div>
        </div>   
        <center>
            <button type="submit" class="btn btn-primary">Создать</button>
        </center>
    </form> 
