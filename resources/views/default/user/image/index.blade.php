@extends('default.layouts.app')

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
  <h2>{{ $thisAlbum->name }}  
      @if(Auth::check())
        <a href="{{ route('zip-album', ['url' => $thisAlbum->url]) }}" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
      @endif
      @if(Setting::get('comment_engine') != 'None')
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#albumComments">Комментарии</button>
      @endif
  </h2>
  <small>{{ $thisAlbum->desc }}</small>
    <br>
    @foreach($thisAlbum->tags as $tag)
        <a href="{{ route('tag', ['tag' => urlencode($tag->name)]) }}" class="btn btn-default btn-xs" role="button">{{ $tag->name }}</a>
    @endforeach
</div>

<div class="row" id="main"> 
    <div id="allImages">
        
        @include('default.user.image.page')
        
    </div>
    
<a id="back-to-top" href="#" 
       class="btn btn-primary btn-lg back-to-top" 
       role="button" 
       title="Нажмите, что бы подняться в верх страницы" 
       data-toggle="tooltip" 
       data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>

</div>


@if(Setting::get('comment_engine') != 'None')
<!-- Modal -->
<div class="modal fade" id="albumComments" tabindex="-1" role="dialog" aria-labelledby="albumCommentsLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="albumCommentsLabel">Комментарии к альбому {{ $thisAlbum->name }}</h4>
      </div>
      <div class="modal-body">

@if(Setting::get('comment_engine') == 'Disqus')

    <div id="disqus_thread"></div>
    <script>
    (function() { // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    s.src = 'https://{{ Setting::get('comment_disqus') }}.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
    })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

@elseif(Setting::get('comment_engine') == 'VK')

    <div id="vk_comments"></div>
    <script type="text/javascript">
    VK.Widgets.Comments("vk_comments", {limit: 20, attach: "*"});
    </script>

@endif          
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endif

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