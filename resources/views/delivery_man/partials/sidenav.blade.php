<div class="sidebar {{ sidebarVariation()['selector'] }} {{ sidebarVariation()['sidebar'] }} {{ @sidebarVariation()['overlay'] }} {{ @sidebarVariation()['opacity'] }}"
     data-background="{{getImage('assets/staff/images/sidebar/2.jpg','400x800')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{route('delivery_man.dashboard')}}" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')"></a>
            <a href="{{route('staff.dashboard')}}" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="@lang('image')"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive('delivery_man.dashboard')}}">
                    <a href="{{route('delivery_man.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('delivery_man.courier.*',3)}}">
                        <i class="menu-icon las la-truck"></i>
                        <span class="menu-title">@lang('Manage Courier')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('delivery_man.courier.*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('delivery_man.courier.index')}} ">
                                <a href="{{route('delivery_man.courier.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('delivery_man.courier.successful.index')}} ">
                                <a href="{{route('delivery_man.courier.successful.index')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Successful couriers')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('delivery_man.courier.mission')}} ">
                                <a href="{{route('delivery_man.courier.mission')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Missions')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('delivery_man.courier.return')}} ">
                                <a href="{{route('delivery_man.courier.return')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Returns')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item {{menuActive('ticket*')}}">
                    <a href="{{route('ticket')}}" class="nav-link"
                       data-default-url="{{ route('ticket') }}">
                        <i class="menu-icon las la-ticket-alt"></i>
                        <span class="menu-title">@lang('Support Ticket')</span>
                    </a>
                </li>
               
            </ul>
        </div>
    </div>
</div>
