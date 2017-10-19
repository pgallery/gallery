@extends('default.layouts.app')

@section('content')

<div class="page-header">
  <h2>Статистика галереи</h2>
</div>

<ul class="list-group">

<li class="list-group-item">
    <span class="badge">{{ $count_users }}</span>
    Пользователей:
</li>

<li class="list-group-item">
    <span class="badge">{{ $count_categories }}</span>
    Категорий:
</li>


<li class="list-group-item">
    <span class="badge">{{ $count_albums }}</span>
    Альбомов:
</li>

<li class="list-group-item">
    <span class="badge">{{ $count_images }} шт. ({{ $summary_images_size }})</span>
    Изображений:
</li>

<li class="list-group-item">
    <span class="badge">-</span>
    Среднестатистический объем изображения:
</li>

<li class="list-group-item">
    <span class="badge">-</span>
    Среднестатистическое разрешение изображения:
</li>

</ul>
    
@endsection