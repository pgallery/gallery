@extends('default.layouts.app')

@section('content')

<div class="row"> 
    
    @foreach($albums as $album)
        
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 thumb" data-toggle="popover" data-placement="bottom" title="{{ $album->name }}" data-content='
                    {{ $album->desc }}<hr>
                    <center><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> {{ $album->year }}
                    <span class="glyphicon glyphicon-picture" aria-hidden="true"></span> {{ $album->imagesCount() }}
                    <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> {{ $album->imagesSumSize() }}</center>
                '>
                
                <p>
                    <a href="{{ route('gallery-show', ['url' => $album->url]) }}"><img src="{{ $album->thumbs->http_thumb_path() }}"  width="{{ $thumbs_width  }}"/></a>
                </p>
                <p>{{ mb_substr($album->name, 0, 20) }}...</p>
            </div>

    @endforeach
    
</div>

@if($tags->count() != 0)
<blockquote>
    <p>
        @foreach($tags as $tag)

        <a href="{{ route('album-showBy', ['option' => 'tag','url' => urlencode($tag->name)]) }}" class="btn btn-default btn-xs" role="button">{{ $tag->name }}</a>

        @endforeach
    </p>
</blockquote>
@endif

@endsection


@section('js-top')

    $(function () {
      $('[data-toggle="popover"]').popover({
          placement: 'bottom',
          trigger: 'hover',
          html: 'true'
      })
    })

@endsection