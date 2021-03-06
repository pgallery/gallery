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
            @if(filter_var(Setting::get('main_site'), FILTER_VALIDATE_URL))
                
                <li><a href="{{ Setting::get('main_site') }}" target="_blank">{{ __('views_layouts_menu.main_site_page') }}</a></li>
            
            @endif
            <li><a href="{{ route('home') }}">{{ __('views_layouts_menu.home_page') }}</a></li>
            
            @if($categories_count > 0)
            
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ __('views_layouts_menu.by_categories_page') }} <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    
                      @if(Roles::is('admin'))
                    
                        @foreach($categories as $category)
                            @if( $category->albumCount() != 0)
                                <li><a href="{{ route('album-showBy', ['option' => 'category', 'url' => urlencode($category->name)]) }}">{{ $category->name }} ({{ $category->albumActiveCount() }})</a></li>
                            @endif
                        @endforeach                      
                      
                      @else
                      
                        @foreach($categories as $category)
                            @if( $category->albumCountPublic() != 0)
                                <li><a href="{{ route('album-showBy', ['option' => 'category', 'url' => urlencode($category->name)]) }}">{{ $category->name }} ({{ $category->albumActiveCountPublic() }})</a></li>
                            @endif
                        @endforeach                      
                      
                      @endif
                      
                  </ul>
                </li>  
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ __('views_layouts_menu.by_years_page') }} <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    @foreach($year_list as $year)

                            <li><a href="{{ route('album-showBy', ['option' => 'year', 'url' => $year->year]) }}">{{ $year->year }}</a></li>

                    @endforeach
                  </ul>
                </li>   
                
                @foreach($custom_menu as $menu)
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $menu->name }} <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    
                      @foreach($menu->tagsRelation() as $mtag)
                        <li><a href="{{ route('album-showBy', ['option' => 'tag','url' => urlencode($mtag->name)]) }}">{{ $mtag->name }} ({{ $mtag->albumsCountRelation() }})</a></li>                
                      @endforeach
                  </ul>
                </li>                
                @endforeach
                                
            @endif  
            
          </ul>

          <ul class="nav navbar-nav navbar-right">


        @if (Auth::check())
                
            
            @if(Roles::is(['admin', 'moderator', 'operator']))
            
                <li><a href="{{ route('admin') }}">Альбомы</a></li>
                
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Администрирование <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    @if(Roles::is('admin'))
                        <li><a href="{{ route('users') }}">Пользователи</a></li>
                        <li><a href="{{ route('menu') }}">Меню</a></li>
                        <li><a href="{{ route('settings') }}">Настройки</a></li>
                        <li><a href="{{ route('tags') }}">Теги</a></li>
                    @endif
                    <li><a href="{{ route('statistics') }}">Статистика</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('flush-cache') }}">Сбросить кэш</a></li>
                  </ul>
                </li>
                
                @if(Roles::is(['admin', 'moderator']))
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> ({{ $summary_trashed }}) <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    @if(Roles::is('admin'))
                        <li><a href="{{ route('show-trash', ['option' => 'users']) }}">Пользователи ({{ $users_trashed }})</a></li>
                        <li><a href="{{ route('show-trash', ['option' => 'categories']) }}">Категории ({{ $categories_trashed }})</a></li>
                    @endif
                    <li><a href="{{ route('show-trash', ['option' => 'albums']) }}">Альбомы ({{ $albums_trashed }})</a></li>
                    <li><a href="{{ route('show-trash', ['option' => 'images']) }}">Фотографии ({{ $images_trashed }})</a></li>
                    @if(Roles::is('admin'))
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('empty-trash') }}">Очистить корзину</a></li>
                    @endif
                  </ul>
                </li>
                @endif
            @endif
                
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{ route('edit-profile') }}">{{ __('views_layouts_menu.edit_profile_page') }}</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('views_layouts_menu.logout_page') }}</a></li>
                  </ul>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                @else
                
                @if( Setting::get('use_ulogin') == 'yes' )
                    <li><div style="margin-top: 18px" id="uLogin_{{ Setting::get('ulogin_id') }}" data-uloginid="{{ Setting::get('ulogin_id') }}"></div></li>
                @endif
                @if( Setting::get('registration') == 'yes' )
                    <li><a href="{{ route('register') }}">{{ __('views_layouts_menu.register_page') }}</a></li>
                @endif
                <li><a href="{{ route('login') }}">{{ __('views_layouts_menu.login_page') }}</a></li>
        @endif

          </ul> 

        </div><!--/.nav-collapse -->
      </div>
    </nav>
    
 