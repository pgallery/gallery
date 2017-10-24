@extends('default.layouts.app')

@section('content')

<div class="row"> 
    
    @foreach($albums as $album)
        
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 thumb">
                
                <p>
                    <a href="{{ route('gallery-show', ['url' => $album->url]) }}"><img src="/images/thumb/{{ $album->url }}/{{ $album->thumbs()->name  }}"  width="{{ $thumbs_width  }}"/></a>
                </p>
                <p>{{ $album->name }}</p>
                <p>
                    <span class="glyphicon glyphicon-camera" aria-hidden="true"></span> {{ $album->year }}
                    <span class="glyphicon glyphicon-picture" aria-hidden="true"></span> {{ $album->imagesCount() }}
                    <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> {{ $album->imagesSumSize() }}
                </p>
            </div>

    @endforeach
    
</div>   

<blockquote>
    <p>
        @foreach($tags as $tag)

        <a href="{{ route('album-showBy', ['option' => 'tag','url' => urlencode($tag->name)]) }}" class="btn btn-default btn-xs" role="button">{{ $tag->name }}</a>

        @endforeach
    </p>
</blockquote>

@endsection