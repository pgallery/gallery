@extends('default.layouts.app')

@section('content')

<div class="page-header">
  <h2>Этап номер три</h2>
</div>

Простыня разная


<nav aria-label="Завершить ознакомление">
  <ul class="pager">
    <li><a href="{{ route('admin') }}">Завершить</a></li>
  </ul>
</nav>

@endsection