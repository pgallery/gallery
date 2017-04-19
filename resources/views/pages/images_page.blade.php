
    @foreach($images as $image)
    
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 thumb" data-Page="{{ ($listImages->currentPage() + 1) }}" data-PageLast="{{ ($listImages->lastPage() + 1) }}">
            <a href="{{ $image['image_url'] }}" data-fancybox="images"> 
                <img  src="{{ $image['thumbs_url'] }}" width="{{ $image['thumbs_width']  }}"/> 
            </a> 
        </div>
    
    @endforeach

