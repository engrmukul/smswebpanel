<!-- BEGIN: Header-->
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="feather icon-menu font-large-1"></i></a></li>
                <li class="nav-item">
                    <a class="navbar-brand" href="{{ route('dashboard') }}">
                        @if(!empty($config->logo) && file_exists("assets/images/".$config->logo))
                            <img class="brand-logo" alt="admin logo" width="0" src="{{ asset("assets/images/".$config->logo) }}">
                        @else
                            <img class="brand-logo" alt="admin logo" src="/assets/app-assets/images/logo/stack-logo-light.png">
                        @endif
                        <h2 class="brand-text">{{ $config->brand_name }}</h2>
                    </a>
                </li>
                <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a></li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="feather icon-menu"></i></a></li>
                    <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon feather icon-maximize"></i></a></li>
                </ul>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <div class="avatar avatar-online">
                                @if(Auth::user()->photo !=null && file_exists('assets/images/users/'.Auth::user()->photo))
                                    <img alt="Profile Image" src="{{ asset('assets/images/users/'.Auth::user()->photo) }}">
                                @else
                                    <img alt="Profile Image" src="/assets/app-assets/images/portrait/small/avatar-s-1.png">
                                @endif
                            </div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('profile.edit', Auth::user()->id) }}">
                                <i class="feather icon-user"></i> Edit Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            @if (Session::has('hasClonedUser'))
                                <a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('cloneuser-logout-form').submit();"><i class="feather icon-power"></i> Return</a>
                                <form id="cloneuser-logout-form" action="{{ route('login.asuser') }}" method="post">
                                    {{ csrf_field() }}
                                </form>
                            @else
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="feather icon-power"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- END: Header-->
