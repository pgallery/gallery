
    @foreach($listImages as $image)
    
        <div 
            class="col-lg-2 col-md-3 col-sm-4 col-xs-6 thumb" 
            data-Page="{{ ($listImages->currentPage() + 1) }}" 
            data-PageLast="{{ $listImages->lastPage() }}">
            <a href="/{{ $upload_dir }}/{{ $thisAlbum->directory }}/{{ $image->name }}" data-fancybox="images"> 
                <img  src="/{{ $thumbs_dir }}/{{ $thisAlbum->directory }}/{{ $image->name }}" width="{{ $thumbs_width  }}"/> 
            </a> 
        
        @if ($show_admin_panel == 1)

            @include('pages.images_admin_menu')
               
        @endif
   
        </div>        
        
    @endforeach

