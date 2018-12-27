    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
             <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <a class="navbar-brand" href="{{ route('home') }}" target="_blank">{{ $gallery_name }}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            @if(filter_var(Setting::get('main_site'), FILTER_VALIDATE_URL))
                
                <li><a href="{{ Setting::get('main_site') }}" target="_blank">{{ __('views_layouts_menu.main_site_page') }}</a></li>
            
            @endif
            <li><a href="{{ route('home') }}" target="_blank">{{ __('views_layouts_menu.home_page') }}</a></li>

          </ul>

          <ul class="nav navbar-nav navbar-right">


        @if (Auth::check())
                
            
            @if(Roles::is(['admin', 'moderator', 'operator']))
            
                <li><a href="{{ route('admin') }}">{{ __('views_layouts_menu.admin_home_page') }}</a></li>
                
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ __('views_layouts_menu.admin_menu_name') }} <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    @if(Roles::is('admin'))
                        <li><a href="{{ route('users') }}">{{ __('views_layouts_menu.admin_users_page') }}</a></li>
                        <li><a href="{{ route('settings') }}">{{ __('views_layouts_menu.admin_settings_page') }}</a></li>
                        <li><a href="{{ route('menu') }}">{{ __('views_layouts_menu.admin_menu_page') }}</a></li>
                        <li><a href="{{ route('tags') }}">{{ __('views_layouts_menu.admin_tags_page') }}</a></li>
                    @endif
                    <li><a href="{{ route('statistics') }}">{{ __('views_layouts_menu.admin_statistics_page') }}</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('flush-cache') }}">{{ __('views_layouts_menu.admin_flushcache_page') }}</a></li>
                  </ul>
                </li>
                
                @if(Roles::is(['admin', 'moderator']))
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> ({{ $summary_trashed }}) <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    @if(Roles::is('admin'))
                        <li><a href="{{ route('show-trash', ['option' => 'users']) }}">{{ __('views_layouts_menu.admin_users_page') }} ({{ $users_trashed }})</a></li>
                        <li><a href="{{ route('show-trash', ['option' => 'categories']) }}">{{ __('views_layouts_menu.admin_categories_page') }} ({{ $categories_trashed }})</a></li>
                    @endif
                    <li><a href="{{ route('show-trash', ['option' => 'albums']) }}">{{ __('views_layouts_menu.admin_albums_page') }} ({{ $albums_trashed }})</a></li>
                    <li><a href="{{ route('show-trash', ['option' => 'images']) }}">{{ __('views_layouts_menu.admin_images_page') }} ({{ $images_trashed }})</a></li>
                    @if(Roles::is('admin'))
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('empty-trash') }}">{{ __('views_layouts_menu.admin_emptytrash_page') }}</a></li>
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
    
 