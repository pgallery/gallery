@extends('template.header')

@section('css')
        .back-to-top {
            cursor: pointer;
            position: fixed;
            bottom: 10px;
            right: 20px;
            display:none;
        } 
@endsection

@section('content')
<div class="page-header">
  <h2>{{ $thisAlbum->name }} </h2>
  <small>{{ $thisAlbum->desc }}</small>
</div>

<div class="row" id="main"> 
    <div id="allImages">
        
        
        @include('pages.images_page')
        

    </div>
    
<a id="back-to-top" href="#" 
       class="btn btn-primary btn-lg back-to-top" 
       role="button" 
       title="Нажмите, что бы подняться в верх страницы" 
       data-toggle="tooltip" 
       data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>

</div>

@endsection

@section('js')

        var loadingHeroes = false;
        
        $(window).scroll(function(){
        
            if ($(this).scrollTop() > 50)
            {
                $('#back-to-top').fadeIn();
            }
            else
            {
                $('#back-to-top').fadeOut();
            }
            
            if ($(this).scrollTop() + $(this).innerHeight() >= $('#main').height()){
                
                var nextPage = $('#allImages .thumb').last().attr('data-Page');
                var lastPage = $('#allImages .thumb').last().attr('data-PageLast');
                
                if(parseInt(nextPage) <= parseInt(lastPage))
                {
                
                    if (!loadingHeroes)
                    {  
                        loadingHeroes = true;
                        $.get('{{ route('gallery-show', ['url' => $thisAlbum->url]) }}?page='+nextPage, {}, function(data) {
                            console.log(nextPage);
                            $(data).insertAfter("#allImages .thumb:last()");
                            loadingHeroes = false;
                        });
                    }
                
                }
            }
        });

        $('#back-to-top').click(function () {
            $('#back-to-top').tooltip('hide');
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
        
        $('#back-to-top').tooltip('show');        
        
@endsection