@extends('default.layouts.app')

@section('content')

<div class="page-header">
  <h2>Статистика галереи</h2>
</div>

<ul class="list-group">

<li class="list-group-item">
    <span class="badge">{{ $count_users }}</span>
    Количество пользователей:
</li>

<li class="list-group-item">
    <span class="badge">{{ $count_groups }}</span>
    Количество групп:
</li>


<li class="list-group-item">
    <span class="badge">{{ $count_albums }}</span>
    Количество альбомов:
</li>

<li class="list-group-item">
    <span class="badge">{{ $count_images }}</span>
    Количество изображений:
</li>

<li class="list-group-item">
    <span class="badge">{{ $summary_images_size }}</span>
    Объем дискового пространства, занимаемого изображениями:
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