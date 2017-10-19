@extends('default.layouts.app')

@section('content')

<h3>Категории <small>
        @if(Helper::isAdmin(Auth::user()->id))<a href="" data-toggle="modal" data-target="#newCategoriesModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small>@endif</h3> 

        <div class="form-group">
            <center>
                <a class="btn btn-success btn-sm" role="button" data-toggle="collapse" 
                   href="#collapseShowCategories" aria-expanded="false" aria-controls="collapseAlbumForm">
                  Отобразить список категорий
                </a>
            </center>
        </div>
        <!-- Start collapseShowCategories -->
        <div class="collapse" id="collapseShowCategories">

@if($categories->count() < 1 and Helper::isAdmin(Auth::user()->id))

    <div class="panel panel-danger">
      <div class="panel-heading">
        <h2 class="panel-title">Отсутствуют категории</h2>
      </div>
      <div class="panel-body">
        Категория - это важный элемент Вашей фотогалереи. Каждый создаваемый Вами альбом 
        должен относиться к одной из созданной Вами категории. У Вас категории отсутствуют,
        поэтому Вы не сможете создать альбом и загрузить фотографции.
        <br></br>
        Для создания категории нажмити кнопку: <a href="" data-toggle="modal" data-target="#newCategoriesModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small>
        <br></br>
        Или перейдите на страницу <a href="{{ route('create') }}">добавление</a>.
      </div>
    </div>

@endif

<table id="group-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Название</th>
                <th>Всего альбомов</th>
                <th>Публичных альбомов</th>
                <th>Закрытых альбомов</th>
                <th>Владелец</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Название</th>
                <th>Всего альбомов</th>
                <th>Публичных альбомов</th>
                <th>Закрытых альбомов</th>
                <th>Владелец</th>
            </tr>

        </tfoot>
        <tbody>
    @foreach($categories as $category)
        
            <tr>
                <td>{{ $category->id }}</td>
                <td> 
                    @if(Helper::isAdmin(Auth::user()->id))
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary  btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('edit-category', ['id' => $category->id]) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-category', ['id' => $category->id]) }}" data-toggle="confirmation" data-title="Удалить категорию, а так же все альбомы и все фотографии?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                      </ul>
                    </div>                    
                    @endif
                     <a href="{{ route('admin', ['options' => 'byCategory', 'id' => $category->id]) }}">{{ $category->name }}</a>
                </td>

                <td>{{ $category->albumCount() }}</td>
                <td>{{ $category->albumCountPublic() }}</td>
                <td>{{ $category->albumCountPrivate() }}</td>
                <td>{{ $category->owner()->name }}</td>
            </tr>

    @endforeach
            
        </tbody>
    </table>

        </div>
        <!-- Start collapseShowCategories -->

<h3>Альбомы <small><a href="" data-toggle="modal" data-target="#newAlbumModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small></h3> 

<table id="album-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Название</th>
                <th>URL</th>
                <th>Миниатюра</th>
                <th>Фотографий</th>
                <th>Объем</th>
                <th>Категория</th>
                <th>Год</th>
                <th>Доступ</th>
                <th>Владелец</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Название</th>
                <th>URL</th>
                <th>Миниатюра</th>
                <th>Фотографий</th>
                <th>Объем</th>
                <th>Категория</th>
                <th>Год</th>
                <th>Доступ</th>
                <th>Владелец</th>
            </tr>

        </tfoot>
        <tbody>
    @foreach($albums as $album)
        
            <tr
            @if($album->images_id == 0)
                 class="danger"
            @endif
            >
                <td>{{ $album->id }}</td>
                <td> 
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="{{ route('edit-album', ['id' => $album->id]) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a></li>
                        @if($album->imagesCount() != 0)
                            <li><a href="{{ route('show-album', ['id' => $album->id]) }}"><span class="glyphicon glyphicon-open" aria-hidden="true"></span> Просмотреть фотографии</a></li>
                            <li><a href="{{ route('rebuild-album', ['id' => $album->id]) }}"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Пересоздать все миниатюры</a></li>
                            <li><a href="{{ route('zip-album', ['url' => $album->url]) }}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Скачать альбом</a></li>
                        @endif
                        <li><a href="{{ route('uploads-album', ['id' => $album->id]) }}"><span class="glyphicon glyphicon-paste" aria-hidden="true"></span> Загрузить фотографии</a></li>
                        @if(Helper::isAdmin(Auth::user()->id))
                            <li><a href="{{ route('sync-album', ['id' => $album->id]) }}"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Синхронизировать из директории</a></li>
                            <li><a href="{{ route('renamedir-album', ['id' => $album->id]) }}"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> Переименовать директорию</a></li>
                            <li><a href="" data-toggle="modal" data-target="#ChangeOwnerAlbumModal" class="clickChangeOwnerAlbum" data-id="{{ $album->id }}" data-owner="{{ $album->owner()->id }}"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Сменить владельца</a></li>
                        @endif
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('delete-album', ['id' => $album->id]) }}" data-toggle="confirmation" data-title="Удалить альбом и все фотографии?"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a></li>
                      </ul>
                    </div>                    
                    
                     {{ $album->name }}
                </td>
                <td>
                    
                    <a href="{{ route('gallery-show', ['url' => $album->url]) }}" class="btn btn-default btn-xs" target="_blank">
                        <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>
                    </a>
                    
                </td>
                <td>
                    @if($album->images_id != 0)
                        <a href="/{{ $thumbs_dir }}/{{ $album->directory }}/{{ $album->thumbs()->name  }}" data-fancybox="images"> 
                            <img  src="/{{ $thumbs_dir }}/{{ $album->directory }}/{{ $album->thumbs()->name  }}" width="75"/> 
                        </a>
                    @endif
                </td>
                <td>{{ $album->imagesCount() }}</td>
                <td>{{ $album->imagesSumSize() }}</td>
                <td>{{ $album->category()->name }}</td>
                <td>{{ $album->year }}</td>
                <td>{{ ($album->permission == 'All' ? "Всем" : "По ссылке") }}</td>
                <td>{{ $album->owner()->name }}</td>
            </tr>

    @endforeach
            
        </tbody>
    </table>


@if(Helper::isAdmin(Auth::user()->id))
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
@endif

<!-- Modal add Album -->
<div class="modal fade" id="newAlbumModal" tabindex="-1" role="dialog" aria-labelledby="newAlbumModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newAlbumModalLabel">Новый альбом</h4>
      </div>
        

      <div class="modal-body">
          
            @include('default.admin.album.create_form')
            
      </div>
          
    </div>
  </div>
</div>

<!-- Modal Change Owner Album -->
<div class="modal fade" id="ChangeOwnerAlbumModal" tabindex="-1" role="dialog" aria-labelledby="ChangeOwnerAlbumModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ChangeOwnerModal">Смена владельца альбома</h4>
      </div>
        
        {!! Form::open([
            'route'     => 'changeowner-album',
            'class'     => 'form-horizontal',
            'method'    => 'POST'
        ]) !!}        
          <input type="hidden"  name="id" id="ChangeOwnerAlbumID" value="">
          
      <div class="modal-body">
                      
            <div class="form-group">
                <label class="col-sm-2 control-label">Владелец:</label>
                <div class="col-sm-8">
                        {!! Form::select('ChangeOwnerAlbumNew', $usersArray, null, array('class' => 'form-control', 'id' => 'ChangeOwnerAlbumNew')) !!}  
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Рекурсивно:</label>
                <div class="col-sm-8">
                        {!! Form::checkbox('ChangeOwnerAlbumRecursion', 'yes') !!}  
                </div>
            </div>              
            
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" data-dismiss="modal">Отмена</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
      </div>
          
      {!! Form::close() !!}
          
    </div>
  </div>
</div>

@endsection

@section('js-top')

        $('a.clickChangeOwnerAlbum').click(function(e){
            $('#ChangeOwnerAlbumID').val(this.getAttribute('data-id'));
            $('#ChangeOwnerAlbumNew').val(this.getAttribute('data-owner'));
            e.preventDefault();
        });

        function Transliterate(input)
        {
            var result = '';
            var curent_sim = '';
            var space = '-';
            var translit = {
            
                <?php echo $transliterateMap; ?>
                
                ' ': space, '_': space, '`': space, '~': space, '!': space, '@': space, '#': space, '$': space,
                '%': space, '^': space, '&': space, '*': space, '(': space, ')': space, '-': space, '\=': space,
                '+': space, '[': space, ']': space, '\\': space, '|': space, '/': space, '.': space, ',': space,
                '{': space, '}': space, '\'': space, '"': space, ';': space, ':': space, '?': space, '<': space,
                '>': space, '№': space					
            }
            
            for(i=0; i < input.length; i++) {
		if(translit[input[i]] != undefined) {
                    if(curent_sim != translit[input[i]] || curent_sim != space){
                        result += translit[input[i]];
                        curent_sim = translit[input[i]];	
                    }
		}
                else {
                    result += input[i];
                    curent_sim = input[i];
		}		
            }
            
            return applyTransformer(result);
        }

        function ToUrl(input)
        {
            var result = '';
            var curent_sim = '';
            var space = '-';
            var translit = {
                
                ' ': space, '_': space, '`': space, '~': space, '!': space, '@': space, '#': space, '$': space,
                '%': space, '^': space, '&': space, '*': space, '(': space, ')': space, '-': space, '\=': space,
                '+': space, '[': space, ']': space, '\\': space, '|': space, '/': space, '.': space, ',': space,
                '{': space, '}': space, '\'': space, '"': space, ';': space, ':': space, '?': space, '<': space,
                '>': space, '№': space					
            }
            
            for(i=0; i < input.length; i++) {
		if(translit[input[i]] != undefined) {
                    if(curent_sim != translit[input[i]] || curent_sim != space){
                        result += translit[input[i]];
                        curent_sim = translit[input[i]];	
                    }
		}
                else {
                    result += input[i];
                    curent_sim = input[i];
		}		
            }
            
            result = result.toLowerCase();
            return applyTransformer(result);
        }
        
        function applyTransformer(string) {
            string = string.replace(/^-/, '');
            return string.replace(/-$/, '');
        }
        
        $('#album_name').keyup(function(eventObject){
        
            $("#album_url").val(ToUrl($(this).val()));
            $("#album_directory").val(Transliterate($(this).val()));
            
        });
        
@endsection