@extends('default.layouts.app')

@section('content')
      
    <div class="page-header">
      <h2>Редактирование категории </h2>
    </div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

      @include('default.admin.category.create_form')
   
@endsection
