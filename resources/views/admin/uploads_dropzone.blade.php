            

    <form 
          action="{{ route('uploads-dropzone') }}"  
          class="dropzone needsclick" 
          id="uploads-dropzone">

        {!! csrf_field() !!}
    
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

            
            
            <div class="fallback">
                <input name="file" type="file" multiple />
            </div>
            
            <div class="dz-message needsclick">
              Drop files here or click to upload.<br />
              <span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
            </div>

            
            
            
    </form>

    