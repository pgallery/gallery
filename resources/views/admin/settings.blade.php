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
            <label class="col-sm-6 control-label">{{ $setting['set_desc'] }}:</label>
            <div class="col-sm-4">
                {!! Form::text("newSetting[$setting[set_name]]", $setting['set_value'], array('class' => 'form-control', 'required')) !!}
            </div>
            <p class="help-block">{{ $setting['set_name'] }}</p>
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
                {!! Form::text('set_name', null, array('class' => 'form-control', 'required')) !!}
            </div>
            <div class="col-xs-6">
                Значение:
                {!! Form::text('set_value', null, array('class' => 'form-control', 'required')) !!}
            </div>
            <div class="col-xs-12">
                Описание:
                {!! Form::text('set_desc', null, array('class' => 'form-control', 'required')) !!}
            </div>
        </div>
        <center>
            {!! Form::submit('Добавить', array('class' => 'btn btn-primary')) !!}
        </center>
    
    {!! Form::close() !!}
    
@endsection
    