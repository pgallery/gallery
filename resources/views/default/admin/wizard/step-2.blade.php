@extends('default.layouts.app')

@section('content')

<div class="page-header">
  <h2>Второй этап, новый Альбом</h2>
</div>

<div class="well">
    
    <p>Для создания категории нажмите кнопку 
        
    <small><a href="" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small>
    
    разположенную ниже. Именно так будет выглядить в дальнейшем создание новой категории.
    </p>
</div>

<h3>Альбомы <small><a href="" data-toggle="modal" data-target="#newAlbumModal" class="btn btn-success btn-xs"><span class=" glyphicon glyphicon-plus" aria-hidden="true"></span></a></small></h3> 

@if($albums_count != 0)
    
<div class="well">
    
    <p><b>Поздравляем!</b> Вы создали первый Альбом. Теперь можно перейти к следующему шагу, загрузке фотографий в Альбом.</p>

</div>

<nav aria-label="Перейти к следующему шагу">
  <ul class="pager">
    <li><a href="{{ route('wizard', ['id' => '3']) }}">Дальше <span aria-hidden="true">&rarr;</span></a></li>
  </ul>
</nav>

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

@endsection

@section('js-top')

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