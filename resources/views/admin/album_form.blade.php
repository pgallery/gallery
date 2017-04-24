@if(isset($type) && $type == 'edit')
    
    {!! Form::open([
        'route'     => ['save-album', $albumId],
        'class'     => 'form-horizontal',
        'method'    => 'POST'
    ]) !!}
    
@else

    {!! Form::open([
        'route'     => 'create-album',
        'class'     => 'form-horizontal',
        'method'    => 'POST'
    ]) !!}
        
@endif
        
        <div class="form-group">
            <label class="col-sm-2 control-label">Название:</label>
            <div class="col-sm-4">
                {!! Form::text('albumName', (!empty($albumName) ? $albumName : ''), array('class' => 'form-control')) !!}
            </div>
            <label class="col-sm-2 control-label">URL:</label>
            <div class="col-sm-4">
                {!! Form::text('albumUrl', (!empty($albumUrl) ? $albumUrl : ''), array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Директория:</label>
            <div class="col-sm-4">
                {!! Form::text('albumUrl', (!empty($albumDir) ? $albumDir : ''), 
                (!empty($albumDir) 
                    ? array_merge(['class' => 'form-control'], ['disabled' => '']) 
                    : array('class' => 'form-control')
                )) !!}
            </div>
            <label class="col-sm-2 control-label">Год:</label>
            <div class="col-sm-4">
                {!! Form::selectRange('albumYear', 2000, 2017, (!empty($albumYear) ? $albumYear : null), ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Группа:</label>
            <div class="col-sm-4">
                {!! Form::select('albumGroup', $groups, 
                (isset($albumGroup)
                    ? $albumGroup
                    : null), array('class' => 'form-control')) !!}
            </div>            
            <label class="col-sm-2 control-label">Права:</label>
            <div class="col-sm-4">
                {!! Form::select('albumPermission', [
                    'All'  => 'Всем',
                    'Url'  => 'По ссылке'
                ], 
                (isset($albumPermission)
                    ? $albumPermission
                    : null), array('class' => 'form-control')) !!}                
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Описание:</label>
            <div class="col-sm-10">
                {!! Form::text('albumDesc', (!empty($albumDesc) ? $albumDesc : ''), array('class' => 'form-control')) !!}
            </div>
        </div>

    <center>
    @if(isset($type) && $type == 'edit')
        {!! Form::submit('Сохранить изменения', array('class' => 'btn btn-primary')) !!}
    @else
        {!! Form::submit('Сохранить изменения', 
        (count($groups) == 0 
            ? array_merge(['class' => 'btn btn-primary'], ['disabled' => '']) 
            : array('class' => 'btn btn-primary')
        )) !!}
    @endif
    </center>

    {!! Form::close() !!}     