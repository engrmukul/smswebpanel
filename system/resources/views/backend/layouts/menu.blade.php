<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            @php($group_id = Auth::user()->id_user_group)
            @foreach(App\Models\Menu::where('status', 'Active')->whereRaw("find_in_set($group_id, user_group_id)", )->orderBy('order_no','ASC')->get() as $menuItem)

                @if( $menuItem->parent_id == 0 )
                    @php($active_route = explode('.',Route::currentRouteName()))
                    <li class="nav-item {{ ($active_route[0] == $menuItem->active_route) ? 'active' : '' }}">
                        <a class="{{ ($menuItem->route_name && $menuItem->route_name == 'dashboard')? '':'ajax-loading' }}" href="{{ $menuItem->children->isEmpty() ? ($menuItem->route_name ? route($menuItem->route_name) : '#') : "#" }}">
                            <i class="{{ $menuItem->icon }}"></i><span class="menu-title" data-i18n="{{ $menuItem->title }}">{{ $menuItem->title }}</span>
                        </a>

                        @if( ! $menuItem->children->isEmpty() )
                            <ul class="menu-content">
                                @foreach($menuItem->children as $subMenuItem)
                                    @if(count($active_route) > 1)
                                        <li class="{{ (\Request::route()->getName() == $subMenuItem->route_name) ? 'active' : (($active_route[1] != $subMenuItem->parent->active_route && $active_route[0].'.'.$active_route[1] == $subMenuItem->active_route)? 'active' : '') }}">
                                    @else
                                        <li class="{{ (\Request::route()->getName() == $subMenuItem->route_name) ? 'active' : '' }}">
                                    @endif
                                            <a class="menu-item ajax-loading" href="{{ route($subMenuItem->route_name) }}" data-i18n="{{ $subMenuItem->title }}">{{ $subMenuItem->title }}</a>
                                        </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
