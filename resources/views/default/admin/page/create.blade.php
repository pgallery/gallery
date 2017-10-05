@extends('default.layouts.app')

@section('content')
    <div class="page-header">
      <h2>Добавление </h2>
    </div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(Helper::isAdmin(Auth::user()->id))
<dl>
    <dt>
        
        <div class="panel panel-default">
          <div class="panel-body">
            Создание группы
          </div>
        </div>        
        
    </dt>
  <dd>
      
      @include('default.admin.group.create_form')
           
    <hr>
  </dd>
@endif
  <dt>
      
        <div class="panel panel-default">
          <div class="panel-body">
            Создание альбома
          </div>
        </div>              
      
      </dt>
  <dd>
      
      @include('default.admin.album.create_form')
   
    <hr>            
  </dd>

  <dt>
      
        <div class="panel panel-default">
          <div class="panel-body">
            Загрузка изображений в альбом
          </div>
        </div>      
      
      </dt>
  <dd>
      
      
      @include('default.admin.upload.index')
      


</dd>
</dl>
@endsection

@section('js-top')

        $(":file").filestyle({
            input: false,
            buttonText: 'Выберите файлы'
        });
        
@endsection        