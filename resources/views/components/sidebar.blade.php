<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="storage/{{ LOGO_PATH.config('settings.logo') }}" class="logo-icon" alt="logo icon">
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        @if(Session::has('menu'))
            @foreach(Session::get('menu') as $menu)
                @if($menu->children->isEmpty())
                    @if($menu->type == 1)
                    <li class="menu-label">{{ $menu->divider_title }}</li>
                    @else
                    <li {{ request()->is($menu->url) ? 'class="mm-active"' : '' }}>
                        <a href="{{ $menu->url ? url($menu->url) : 'javascript:void(0)' }}">
                            <div class="parent-icon"><i class='{{ $menu->icon_class }}' style="font-size: 16px;"></i>
                            </div>
                            <div class="menu-title">{{ $menu->module_name }}</div>
                        </a>
                    </li>
                    @endif
                @else
                <li class="{{ request()->is($menu->url) ? 'mm-active' : '' }}">
                    <a href="javascript:void(0)" class="has-arrow">
                        <div class="parent-icon"><i class='{{ $menu->icon_class }}' style="font-size: 16px;"></i>
                        </div>
                        <div class="menu-title">{{ $menu->module_name }}</div>
                    </a>
                    <ul>
                        @foreach($menu->children as $submenu)
                        <li class="{{ request()->is($submenu->url) ? 'mm-active' : '' }}">
                            <a href="{{ $submenu->url ? url($submenu->url) : 'javascript:void(0)' }}">
                                <i class="{{ $submenu->icon_class }}" style="font-size: 16px;"></i>
                                {{ $submenu->module_name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endif
            @endforeach
        @endif
    </ul>
    <!--end navigation-->
</div>