@extends('default.layouts.app')

@section('content')

<div class="page-header">
  <h2>Первый этап, новая Категория</h2>
</div>

<div class="well">
    
    <p>Категория упрощает сортировку фотографий в галереи, является важным элементом. 
    Например вы можете создать категорию "Праздники", а в них поместить альбомы:
    "День военно морского флота", "Новый год", "День победы", "День рождения". 
    В категорию "Путешествия" добавить такие альбомы как: "Анапа", "Байкал" и т.д.</p>

    <p>В дальнейшем Вы можете переименовать категорию, добавить новые, сменить 
    категорию альбома.</p>
    
    <p>Для создания категории нажмите кнопку 
        
    <small><a href="" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small>
    
    разположенную ниже. Именно так будет выглядить в дальнейшем создание новой категории.
    </p>
</div>

<h3>Категории <small><a href="" data-toggle="modal" data-target="#newCategoriesModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small></h3> 

@if($categories_count != 0)
    
<div class="well">
    
    <p><b>Поздравляем!</b> Вы создали первую категорию. Теперь можно перейти к следующему шагу, созданию Альбома.</p>

</div>

<nav aria-label="Перейти к следующему шагу">
  <ul class="pager">
    <li><a href="{{ route('wizard', ['id' => '2']) }}">Дальше <span aria-hidden="true">&rarr;</span></a></li>
  </ul>
</nav>

@endif

<!-- Modal add Group -->
<div class="modal fade" id="newCategoriesModal" tabindex="-1" role="dialog" aria-labelledby="newCategoriesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newCategoriesModalLabel">Новая категория</h4>
      </div>
        
      <div class="modal-body">
          
         @include('default.admin.category.create_form')
            
      </div>
          
    </div>
  </div>
</div>

@endsection