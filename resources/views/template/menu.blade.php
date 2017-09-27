    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
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
                    
                      @if(Auth::check() && Helper::isAdmin(Auth::user()->id))
                    
                        @foreach($group_list as $group)
                            @if( $group->albumCount() != 0)
                                <li><a href="{{ route('album-showBy', ['option' => 'byGroup', 'id' => $group->id]) }}">{{ $group->name }} ({{ $group->albumCount() }})</a></li>
                            @endif
                        @endforeach                      
                      
                      @else
                      
                        @foreach($group_list as $group)
                            @if( $group->albumCountPublic() != 0)
                                <li><a href="{{ route('album-showBy', ['option' => 'byGroup', 'id' => $group->id]) }}">{{ $group->name }} ({{ $group->albumCountPublic() }})</a></li>
                            @endif
                        @endforeach                      
                      
                      @endif
                      
                  </ul>
                </li>  
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">По годам <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    @foreach($year_list as $year)

                            <li><a href="{{ route('album-showBy', ['option' => 'byYear', 'id' => $year['year']]) }}">{{ $year['year'] }}</a></li>

                    @endforeach
                  </ul>
                </li>                
          </ul>

          <ul class="nav navbar-nav navbar-right">


        @if (Auth::check())
                
            
            @if(Helper::isAdminMenu(Auth::user()->id))
            
                <li><a href="{{ route('admin') }}">Список</a></li>
                
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Управление <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{ route('create') }}">Добавление</a></li>
                    @if(Helper::isAdmin(Auth::user()->id))
                        <li><a href="{{ route('users') }}">Пользователи</a></li>
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
                        <li><a href="{{ route('show-trash', ['option' => 'users']) }}">Пользователи ({{ $users_trashed }})</a></li>
                        <li><a href="{{ route('show-trash', ['option' => 'groups']) }}">Группы ({{ $groups_trashed }})</a></li>
                    @endif
                    <li><a href="{{ route('show-trash', ['option' => 'albums']) }}">Альбомы ({{ $albums_trashed }})</a></li>
                    <li><a href="{{ route('show-trash', ['option' => 'images']) }}">Фотографии ({{ $images_trashed }})</a></li>
                    @if(Helper::isAdmin(Auth::user()->id))
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('empty-trash') }}">Очистить корзину</a></li>
                    @endif
                  </ul>
                </li>                 
            
            @endif
                
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{ route('edit-profile') }}">Редактировать профиль</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выход</a></li>
                  </ul>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                @else
                
                @if( Setting::get('use_ulogin') == 'yes' )
                    <li><div style="margin-top: 18px" id="uLogin_{{ Setting::get('ulogin_id') }}" data-uloginid="{{ Setting::get('ulogin_id') }}"></div></li>
                @endif
                @if( Setting::get('registration') == 'yes' )
                    <li><a href="{{ route('register') }}">Регистрация</a></li>
                @endif
                <li><a href="{{ route('login') }}">Вход</a></li>
        @endif

          </ul> 

        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
 