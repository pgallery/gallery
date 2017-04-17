        <form method="post" action="{{ route('uploads') }}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            
            @if(isset($type) && $type == 'thisAlbum')
                <input type="hidden" name="album_id" value="{{ $album_id }}">
            @else
                <select class="form-control" name="album_id"
                @if (count($albums) == 0)
                        disabled
                @endif>
                    @foreach($albums as $album)
                        <option value="{{ $album->id }}">{{ $album->name }}</option>
                    @endforeach
                </select>
                <p class="help-block">Выбираем фотоальбом, в который добавить файлы.</p>
            @endif
                
                <input type="file" multiple name="file[]">
                <p class="help-block">Выбираем все необходимые для загрузки файлы.</p>
                <div class="checkbox">
                    <label>
                      <input type="checkbox" name="replace"> Заменять существующие файлы
                    </label>
                </div>            

            <button type="submit" class="btn btn-primary"
            @if (empty($type) && count($albums) == 0)
                    disabled
            @endif>Загрузить выбранные файлы</button>
        </form>
