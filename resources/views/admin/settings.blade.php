@extends('template.header')

@section('content')
      
<div class="page-header">
  <h2>Изменение настроек</h2>
</div>


    {!! Form::open([
        'route'     => 'save-settings',
        'class'     => 'form-horizontal',
        'method'    => 'POST'
    ]) !!}     
    
    @foreach($settings as $setting)
        
        <div class="form-group">
            <label class="col-sm-2 control-label">{{ $setting['set_name'] }}:</label>
            <div class="col-sm-4">
                {!! Form::text("newSetting[$setting[set_name]]", $setting['set_value'], array('class' => 'form-control')) !!}
            </div>
            <p class="help-block">{{ $setting['set_desc'] }}</p>
        </div>    

    @endforeach
    <hr>
    <center>
        {!! Form::submit('Сохранить изменения', array('class' => 'btn btn-primary')) !!}
    </center>
    
    {!! Form::close() !!}   

<div class="page-header">
  <h2>Добавление параметра</h2>
</div>

    {!! Form::open([
        'route'     => 'create-settings',
        'class'     => 'form-horizontal',
        'method'    => 'POST'
    ]) !!}  
        <div class="form-group">
            <div class="col-xs-6">
                Наименование ключа:
                {!! Form::text('key', null, array('class' => 'form-control')) !!}
            </div>
            <div class="col-xs-6">
                Значение:
                {!! Form::text('value', null, array('class' => 'form-control')) !!}
            </div>
            <div class="col-xs-12">
                Описание:
                {!! Form::text('desc', null, array('class' => 'form-control')) !!}
            </div>
        </div>
        <center>
            {!! Form::submit('Добавить', array('class' => 'btn btn-primary')) !!}
        </center>
    
    {!! Form::close() !!}
    
@endsection
    