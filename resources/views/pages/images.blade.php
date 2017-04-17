@extends('template.header')

@section('content')
<div class="page-header">
  <h2>{{ $album_name }} </h2>
  <small>{{ $album_desc }}</small>
</div>

<div class="row"> 
<p>
    @foreach($images as $image)
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 thumb">
            <a href="{{ $image['image_url'] }}" data-fancybox="images"> 
                <img  src="{{ $image['thumbs_url'] }}" width="{{ $image['thumbs_width']  }}"/> 
            </a> 
        </div>
    @endforeach
</p>  
</div>

@endsection