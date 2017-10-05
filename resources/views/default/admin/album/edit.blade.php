@extends('default.layouts.app')

@section('content')
    <div class="page-header">
      <h2>Редактирование альбома </h2>
    </div>
      @include('default.admin.album.create_form')
   
@endsection
