@extends('default.layouts.app')

@section('content')

<div class="page-header">
  <h2>Этап номер два</h2>
</div>

Простыня разная


<nav aria-label="Перейти к следующему шагу">
  <ul class="pager">
    <li class="next"><a href="{{ route('wizard', ['id' => '3']) }}">Дальше <span aria-hidden="true">&rarr;</span></a></li>
  </ul>
</nav>

@endsection