@if(isset($type) && $type == 'edit')

    {!! Form::model($category, [
        'method'    => 'POST',
        'route'     => ['save-category', $category->id],
        'class'     => 'form-horizontal',
        'id'        => 'GroupForm',
    ]) !!}    
    
@else

    {!! Form::open([
        'route'       => 'create-category',
        'class'       => 'form-horizontal',
        'method'      => 'POST',
        'id'          => 'GroupForm2',

    ]) !!}        
        
@endif
        
        <div class="form-group">
            <label class="col-sm-4 control-label">Название категории:</label>
            <div class="col-sm-6">
                {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name', 'required')) !!}
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-default" data-dismiss="modal">Отмена</button>

            {!! Form::submit((isset($type) && $type == 'edit' ? 'Сохранить изменения' : 'Создать категорию' ), array('class' => 'btn btn-primary')) !!}       

        </div>

    {!! Form::close() !!}
