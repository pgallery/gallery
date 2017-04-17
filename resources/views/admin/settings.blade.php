@extends('template.header')

@section('content')
      
<form class="form-horizontal" action="{{ route('save-settings') }}" method="POST">

    @foreach($settings as $setting)
        
        <div class="form-group">
            <label class="col-sm-2 control-label">{{ $setting['set_name'] }}:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="newSetting[{{ $setting['set_name'] }}]" value="{{ $setting['set_value'] }}">
            </div>
            <p class="help-block">{{ $setting['set_desc'] }}</p>
        </div>    

    @endforeach
    
    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
</form>    

<hr>

<form class="form-horizontal" action="{{ route('create-settings') }}" method="POST">

        <div class="col-xs-6">
            Key:
            <input type="text" class="form-control" name="key">
        </div>
        <div class="col-xs-6">
            Value:
            <input type="text" class="form-control" name="value">
        </div>
        <div class="col-xs-12">
            Desc:
            <input type="text" class="form-control" name="desc">
        </div>
        
        <button type="submit" class="btn btn-primary">Добавить</button>
    
</form> 
@endsection
    