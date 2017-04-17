@extends('template.header')

@section('content')


<dl>
  <dt>Создание группы</dt>
  <dd>
      
      @include('admin.group_form')
           
    <hr>
  </dd>

  <dt>Создание альбома</dt>
  <dd>
      
      @include('admin.album_form')
   
    <hr>            
  </dd>

  <dt>Загрузка изображений в альбом</dt>
  <dd>
      
      
      @include('admin.uploads_images')
      


</dd>
</dl>
@endsection
