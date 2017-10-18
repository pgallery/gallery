@extends('default.layouts.app')

@section('content')
    <div class="page-header">
      <h2>Добавление </h2>
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

@if(Helper::isAdmin(Auth::user()->id))
<dl>
    <dt>
        
        <div class="panel panel-default">
          <div class="panel-body">
            Создание категории
          </div>
        </div>        
        
    </dt>
  <dd>
      
      @include('default.admin.category.create_form')
           
    <hr>
  </dd>
@endif
  <dt>
      
        <div class="panel panel-default">
          <div class="panel-body">
            Создание альбома
          </div>
        </div>              
      
      </dt>
  <dd>
      
      @include('default.admin.album.create_form')
   
    <hr>            
  </dd>

  <dt>
      
        <div class="panel panel-default">
          <div class="panel-body">
            Загрузка изображений в альбом
          </div>
        </div>      
      
      </dt>
  <dd>
      
      
      @include('default.admin.upload.index')
      


</dd>
</dl>
@endsection

@section('js-top')

        $(":file").filestyle({
            input: false,
            buttonText: 'Выберите файлы'
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
