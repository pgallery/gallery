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

<ul class="nav nav-tabs">
  @foreach($settings_groups as $setting_group)
  <li
  @if($setting_group->setgroup_key == 'base')
    class="active"
  @endif
  >
      <a
      @if($setting_group->setgroup_key == 'base')
         data-toggle="tab"
      @endif      
      data-toggle="tab" href="#{{ $setting_group->setgroup_key }}">
          {{ $setting_group->setgroup_name }} ({{ $setting_group->settingsCount() }})
      </a>
  </li>
  
  @endforeach

  
</ul>

<div class="tab-content">
    
  @foreach($settings_groups as $setting_group)

    <div id="{{ $setting_group->setgroup_key }}"
      @if($setting_group->setgroup_key == 'base')
            class="tab-pane fade in active"
      @else
            class="tab-pane fade"
      @endif         
    >
    
        <div class="page-header">
            <h6>{{ $setting_group->setgroup_desc }}</h6>
        </div>


        @foreach($setting_group->settings() as $setting)

            <div class="form-group">
                <label class="col-sm-6 control-label">{{ $setting->set_desc }}:</label>
                <div class="col-sm-4"> 
                    @if($setting->set_type == 'yesno')
                        {!! Form::select("newSetting[$setting->set_name]", [
                            'yes'   => 'Включено',
                            'no'    => 'Отключено'
                        ], $setting['set_value'], array('class' => 'form-control')) !!}   
                    @else
                        {!! Form::text("newSetting[$setting->set_name]", $setting->set_value, array('class' => 'form-control', 'required')) !!}
                    @endif

                </div>
                @if($setting->set_tooltip)
                <!--<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="left" title="{{ $setting->set_tooltip }}">-->
                    <span class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="{{ $setting->set_tooltip }}"></span>
                <!--</button>-->
                @endif
            </div>    

        @endforeach
        
    </div>  
  
  @endforeach    
  
</div>

    <hr>
    <center>
        {!! Form::submit('Сохранить изменения', array('class' => 'btn btn-primary')) !!}
    </center>
    
    {!! Form::close() !!}   
    
@endsection

@section('js-top')
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
@endsection