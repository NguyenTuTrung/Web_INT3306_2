<div class="sidebar {{ sidebarVariation()['selector'] }} {{ sidebarVariation()['sidebar'] }} {{ @sidebarVariation()['overlay'] }} {{ @sidebarVariation()['opacity'] }}"
     data-background="{{getImage('assets/staff/images/sidebar/2.jpg','400x800')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{route('staff_warehouse.dashboard')}}" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')"></a>
            <a href="{{route('staff_warehouse.dashboard')}}" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="@lang('image')"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive('staff_warehouse.dashboard')}}">
                    <a href="{{route('staff_warehouse.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{menuActive('staff_warehouse.courier.create')}}">
                    <a href="{{route('staff_warehouse.courier.create')}}" class="nav-link"
                       data-default-url="{{ route('staff_warehouse.courier.create') }}">
                        <i class="menu-icon las la-paper-plane"></i>
                        <span class="menu-title">@lang('Courier Send')</span>
                    </a>
                </li>


                 <li class="sidebar-menu-item {{menuActive('staff_warehouse.courier*')}}">
                    <a href="{{route('staff_warehouse.courier.list')}}" class="nav-link"
                       data-default-url="{{ route('staff_warehouse.courier.list') }}">
                        <i class="menu-icon las la-share"></i>
                        <span class="menu-title">@lang('Manage Courier')</span>
                    </a>
                </li>


                <li class="sidebar-menu-item {{menuActive('staff_warehouse.delivery.list')}}">
                    <a href="{{route('staff_warehouse.delivery.list')}}" class="nav-link"
                       data-default-url="{{ route('staff_warehouse.delivery.list') }}">
                        <i class="menu-icon las la-truck-loading"></i>
                        <span class="menu-title">@lang('Upcoming Courier')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('staff_warehouse.warehouse*')}}">
                    <a href="{{route('staff_warehouse.warehouse.index')}}" class="nav-link"
                       data-default-url="{{ route('staff_warehouse.warehouse.index') }}">
                        <i class="menu-icon las la-code-branch"></i>
                        <span class="menu-title">@lang('Warehouse List')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('staff.cash.income')}}">
                    <a href="{{route('staff.cash.income')}}" class="nav-link"
                       data-default-url="{{ route('staff.cash.income') }}">
                        <i class="menu-icon las la-wallet"></i>
                        <span class="menu-title">@lang('Cash Collection')</span>
                    </a>
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
