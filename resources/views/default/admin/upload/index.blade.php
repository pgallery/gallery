            
    {!! Form::open([
        'route'     => 'uploads',
        'class'     => 'form-horizontal',
        'method'    => 'POST',
        'files' => true
    ]) !!}             
            
            @if(isset($type) && $type == 'thisAlbum')
            
               {!! Form::hidden('album_id', $album_id) !!} 
               
            @else
            
                <div class="form-group">
                    <label class="col-sm-4 control-label">Наименование альбома:</label>
                    <div class="col-sm-6">
                        {!! Form::select('album_id', $albumsArray, null, (empty($type) && count($albumsArray) == 0 
                            ? array_merge(['placeholder' => 'Отсутствуют альбомы', 'class' => 'form-control'], ['disabled' => '']) 
                            : array('class' => 'form-control')
                        )) !!}
                    </div>
                </div>

            @endif
                
                <div class="form-group">
                    <label class="col-sm-4 control-label">Выберите файлы:</label>
                    <div class="col-sm-6">
                        {!! Form::file('file[]', array('class' => 'filestyle', 'data-input' => 'false', 'multiple' => true)) !!}
                    </div>
                </div>            
            
                <div class="form-group">
                    <label class="col-sm-4 control-label">Заменять существующие файлы:</label>
                    <div class="col-sm-6">
                        {!! Form::checkbox('replace', 'yes', false) !!}
                    </div>
                </div>                 

            <center>
                
                {!! Form::submit('Загрузить выбранные файлы', 
                (empty($type) && count($albumsArray) == 0 
                    ? array_merge(['class' => 'btn btn-primary'], ['disabled' => '']) 
                    : array('class' => 'btn btn-primary')
                )) !!}
        
            </center>

    {!! Form::close() !!}            
