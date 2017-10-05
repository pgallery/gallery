@if(isset($type) && $type == 'edit')

    {!! Form::model($group, [
        'method'    => 'POST',
        'route'     => ['save-group', $group->id],
        'class'     => 'form-horizontal',
        'id'        => 'GroupForm',
    ]) !!}    
    
@else

    {!! Form::open([
        'route'       => 'create-group',
        'class'       => 'form-horizontal',
        'method'      => 'POST',
        'id'          => 'GroupForm2',

    ]) !!}        
        
@endif
        
        <div class="form-group">
            <label class="col-sm-4 control-label">Название группы:</label>
            <div class="col-sm-6">
                {!! Form::text('name', null, array('class' => 'form-control', 'required')) !!}
            </div>
        </div>
        <center>
            {!! Form::submit((isset($type) && $type == 'edit' ? 'Сохранить изменения' : 'Создать группу' ), array('class' => 'btn btn-primary')) !!}       
        </center>

    {!! Form::close() !!}
