        <form method="post" action="{{ route('uploads') }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            
            @if(isset($type) && $type == 'thisAlbum')
                <input type="hidden" name="album_id" value="{{ $album_id }}">
            @else
            
                <div class="form-group">
                    <label class="col-sm-4 control-label">Группа добавляемого альбома:</label>
                    <div class="col-sm-6">
                        
                        <select class="form-control" name="album_id"
                        @if (count($albums) == 0)
                                disabled
                        @endif>
                            @foreach($albums as $album)
                                <option value="{{ $album->id }}">{{ $album->name }}</option>
                            @endforeach
                        </select>                        
                        
                    </div>
                </div>            

            @endif
                
                <div class="form-group">
                    <label class="col-sm-4 control-label">Выберите файлы:</label>
                    <div class="col-sm-6">
                        
                        <input type="file" multiple name="file[]" class="filestyle" data-input="false">
                        
                    </div>
                </div>            
            
                
                <div class="form-group">
                    <label class="col-sm-4 control-label">Заменять существующие файлы:</label>
                    <div class="col-sm-6">
                        
                        <input type="checkbox" name="replace"> 
                        
                    </div>
                </div>                 
                
                
          

            <center>
            <button type="submit" class="btn btn-primary"
            @if (empty($type) && count($albums) == 0)
                    disabled
            @endif>Загрузить выбранные файлы</button>
            </center>
        </form>
