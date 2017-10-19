@extends('default.layouts.app')

@section('content')

<div class="page-header">
  <h2>Третий этап, загрузка фотографий</h2>
</div>

@include('default.admin.upload.dropzone')

<nav aria-label="Завершить ознакомление">
  <ul class="pager">
    <li><a href="{{ route('admin') }}">Завершить</a></li>
  </ul>
</nav>

@endsection
