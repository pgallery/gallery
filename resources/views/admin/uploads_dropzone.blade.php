            
    {!! Form::open([
        'route'     => ['uploads', 'dropzone'],
        'class'     => 'dropzone needsclick',
        'id'        => 'uploads-dropzone'
    ]) !!}

    {!! Form::hidden('album_id', $album_id) !!} 
            
    <div class="fallback">
        {!! Form::file('file', array('multiple' => true)) !!}
    </div>

    <div class="dz-message needsclick">
        <h4 style="text-align: center;color:#428bca;">Перетащите фотографии в эту область или нажмите на неё для их выбора  <span class="glyphicon glyphicon-hand-down"></span></h4>
    </div>

    {!! Form::close() !!}
    