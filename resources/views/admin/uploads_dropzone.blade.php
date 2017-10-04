            
    {!! Form::open([
        'route'     => 'uploads-dropzone',
        'class'     => 'dropzone needsclick',
        'id'        => 'uploads-dropzone'
    ]) !!}         

    {!! Form::hidden('album_id', $album_id) !!} 
            
    <div class="fallback">
        {!! Form::file('file', array('multiple' => true)) !!}
    </div>

    <div class="dz-message needsclick">
        Перетащите фотографии в эту область или нажмите на неё для их выбора.<br />
    </div>

    {!! Form::close() !!}
    