@extends('default.layouts.app')

@section('content')

<div class="page-header">
  <h2>Статистика галереи</h2>
</div>

<div class="row">
  <div class="col-md-8">Количество пользователей:</div>
  <div class="col-md-4">{{ $count_users }}</div>
</div>
   

<div class="row">
  <div class="col-md-8">Количество групп:</div>
  <div class="col-md-4">{{ $count_groups }}</div>
</div>

<div class="row">
  <div class="col-md-8">Количество альбомов:</div>
  <div class="col-md-4">{{ $count_albums }}</div>
</div>

<div class="row">
  <div class="col-md-8">Количество изображений:</div>
  <div class="col-md-4">{{ $count_images }}</div>
</div>

<div class="row">
  <div class="col-md-8">Объем дискового пространства, занимаемого изображениями:</div>
  <div class="col-md-4">{{ $summary_images_size }}</div>
</div>

<div class="row">
  <div class="col-md-8">Среднестатистический объем изображения:</div>
  <div class="col-md-4">0</div>
</div>

<div class="row">
  <div class="col-md-8">Среднестатистическое разрешение изображения:</div>
  <div class="col-md-4">0</div>
</div>

@endsection