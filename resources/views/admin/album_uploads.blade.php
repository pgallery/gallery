@extends('template.header')

@section('content')

    <div class="page-header">
      <h2>Загрузка изображений в альбом: {{ $album_name }} </h2>
    </div>

      @include('admin.uploads_images')
   
@endsection

@section('js-top')

        $(":file").filestyle({
            input: false,
            buttonText: 'Выберите файлы'
        });
        
@endsection   