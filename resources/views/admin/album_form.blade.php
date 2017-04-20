@if(isset($type) && $type == 'edit')
    <form class="form-horizontal" action="{{ route('save-album', ['id' => $albumId]) }}" method="POST">
        
@else
    <form class="form-horizontal" action="{{ route('create-album') }}" method="POST">

@endif
        

        <div class="form-group">
            <label class="col-sm-2 control-label">Название:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="albumName" @isset($albumName) value="{{ $albumName }}" @endisset>
            </div>
            <label class="col-sm-2 control-label">URL:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="albumUrl" @isset($albumUrl) value="{{ $albumUrl }}" @endisset>     
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Директория:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="albumDir" @isset($albumDir) value="{{ $albumDir }}" disabled @endisset>   
            </div>
            <label class="col-sm-2 control-label">Год:</label>
            <div class="col-sm-4">
                <select class="form-control" name="albumYear">

                    @for ($i = 2000; $i < 2018; $i++)
                        <option value="{{ $i }}" @if(isset($albumYear) && $i == $albumYear) selected @endif>{{ $i }}</option>
                    @endfor

                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Группа:</label>
            <div class="col-sm-4">
                <select class="form-control" name="albumGroup"
                    @if (count($groups) == 0)
                        disabled
                    @endif>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" @if(isset($albumPermission) && $group->id == $albumGroup) selected @endif>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>            
            <label class="col-sm-2 control-label">Права:</label>
            <div class="col-sm-4">
                
                <select class="form-control" name="albumPermission">
                    <option value="All" @if(isset($albumPermission) && 'All' == $albumPermission) selected @endif>Всем</option>
                    <option value="Url" @if(isset($albumPermission) && 'Url' == $albumPermission) selected @endif>По ссылке</option>
                </select>                
                
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Описание:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="albumDesc" @isset($albumDesc) value="{{ $albumDesc }}" @endisset>    
            </div>
        </div>

<center>
@if(isset($type) && $type == 'edit')
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        
@else
        <button type="submit" class="btn btn-primary"
        @if (count($groups) == 0)
            disabled
        @endif>Создать</button>

@endif
</center>

    </form>       