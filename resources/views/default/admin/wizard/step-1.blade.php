@extends('default.layouts.app')

@section('content')

<div class="page-header">
  <h2>Этап номер один</h2>
</div>

Простыня разная

<h3>Категории <small>
        @if(Helper::isAdmin(Auth::user()->id))<a href="" data-toggle="modal" data-target="#newCategoriesModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small>@endif</h3> 

<nav aria-label="Перейти к следующему шагу">
  <ul class="pager">
    <li class="next"><a href="{{ route('wizard', ['id' => '2']) }}">Дальше <span aria-hidden="true">&rarr;</span></a></li>
  </ul>
</nav>

@endsection