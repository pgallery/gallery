@extends('template.header')

@section('content')
<div class="page-header">
  <h2>{{ $album_name }} </h2>
  <small>{{ $album_desc }}</small>
</div>

<div class="row" id="main"> 
    <div id="allImages">
        
        
        @include('pages.images_page')
        

    </div>
</div>



@endsection

@section('js')

        var loadingHeroes = false;
        
        $(window).scroll(function(){
            if ($(this).scrollTop() + $(this).innerHeight() >= $('#main').height()){
                if (!loadingHeroes) {
                    loadingHeroes = true;
                    $.get('/gallery/showHero-{{ $album_url }}?page='+$('#allImages .thumb').last().attr('data-Page'), {}, function(data) {
                        console.log('GET: /gallery/showHero-{{ $album_url }}?page='+$('#allImages .thumb').last().attr('data-Page'));
                        $(data).insertAfter("#allImages .thumb:last()");
                        loadingHeroes = false;
                    }); 
                }
              }
        });

@endsection