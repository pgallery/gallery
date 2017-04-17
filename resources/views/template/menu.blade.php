    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ route('home') }}">{{ $gallery_name }}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="{{ route('home') }}">Галерея</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">По группам <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                      
                    @foreach($group_list as $group)
                        @if( $group->albumCountPublic() != 0)
                            <li><a href="{{ url('/album/byGroup/' . $group['id']) }}">{{ $group->name }}</a></li>
                        @endif
                    @endforeach

                  </ul>
                </li>  
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">По годам <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    @foreach($year_list as $year)

                            <li><a href="{{ url('/album/byYear/' . $year['year']) }}">{{ $year['year'] }}</a></li>

                    @endforeach
                  </ul>
                </li>                
          </ul>            

          <ul class="nav navbar-nav navbar-right">

            @if (Route::has('login'))
                @if (Auth::check())
                
                <li><a href="{{ route('admin') }}">Список</a></li>
                
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Управление <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{ route('create') }}">Добавление</a></li>
                    @if(Helper::isAdmin(Auth::user()->id))
                        <li><a href="{{ route('settings') }}">Настройки</a></li>
                    @endif
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('flush-cache') }}">Сбросить кэш</a></li>
                  </ul>
                </li>                
                
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> ({{ $summary_trashed }}) <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    @if(Helper::isAdmin(Auth::user()->id))
                        <li><a href="{{ route('groups-trash') }}">Группы ({{ $groups_trashed }})</a></li>
                    @endif
                    <li><a href="{{ route('albums-trash') }}">Альбомы ({{ $albums_trashed }})</a></li>
                    <li><a href="{{ route('images-trash') }}">Фотографии ({{ $images_trashed }})</a></li>
                    @if(Helper::isAdmin(Auth::user()->id))
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('empty-trash') }}">Очистить корзину</a></li>
                    @endif
                  </ul>
                </li>                 
                
                <li><a href="{{ route('logout') }}">Выход ({{ Auth::user()->name }})</a></li>
                @else
                <li><a href="{{ route('login') }}">Вход</a></li>
                @endif
            @endif
              
              
          </ul> 

        </div><!--/.nav-collapse -->
      </div>
    </nav>