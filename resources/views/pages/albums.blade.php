@extends('template.header')

@section('content')

<div class="row"> 
    
    @foreach($albums as $album)
        
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 thumb">
                
                <p>
                    <a href="{{ route('gallery-show', ['url' => $album['url']]) }}"><img src="{{ $album['thumbs_url']  }}"  width="{{ $album['thumbs_width']  }}"/></a>
                </p>
                <p>{{ $album['name'] }}</p>
                <p>
                    <span class="glyphicon glyphicon-camera" aria-hidden="true"></span> {{ $album['year'] }}
                    <span class="glyphicon glyphicon-picture" aria-hidden="true"></span> {{ $album['count'] }}
                    <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> {{ $album['size'] }}
                </p>
            </div>

    @endforeach
    
</div>    

@endsection