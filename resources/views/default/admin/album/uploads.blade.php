@extends('default.layouts.app')

@section('content')

    <div class="page-header">
      <h2>Загрузка изображений в альбом: {{ $album_name }} </h2>
    </div>

    @include('default.admin.upload.dropzone')
   
@endsection

@section('js-top')

        $(":file").filestyle({
            input: false,
            buttonText: 'Выберите файлы'
        });

        
@endsection