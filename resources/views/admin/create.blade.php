@extends('template.header')

@section('content')
    <div class="page-header">
      <h2>Добавление </h2>
    </div>

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
      
      @include('admin.group_form')
           
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
      
      @include('admin.album_form')
   
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
      
      
      @include('admin.uploads_images')
      


</dd>
</dl>
@endsection

@section('js-top')

        $(":file").filestyle({
            input: false,
            buttonText: 'Выберите файлы'
        });
        
@endsection        