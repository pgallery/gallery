
    @foreach($listImages as $image)
    
        <div 
            class="col-lg-2 col-md-3 col-sm-4 col-xs-6 thumb" 
            data-Page="{{ ($listImages->currentPage() + 1) }}" 
            data-PageLast="{{ $listImages->lastPage() }}">
            
        @if(Agent::isMobile())
            <a href="{{ $image->http_mobile_path() }}" data-fancybox="images"> 
        @else
            <a href="{{ $image->http_path() }}" data-fancybox="images"> 
        @endif
                <img  src="{{ $image->http_thumb_path() }}" width="{{ $thumbs_width  }}"/> 
            </a>    
        
        @if ($show_admin_panel == 1)
        
            @include('default.layouts.image_admin_menu')
               
        @endif
   
        </div>        
        
    @endforeach

