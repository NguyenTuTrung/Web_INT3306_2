@extends('manager_warehouse.layouts.master')
@section('content')
    <div class="page-wrapper default-version">
        @include('manager_warehouse.partials.sidenav')
        @include('manager_warehouse.partials.topnav')
        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                @include('manager_warehouse.partials.breadcrumb')
                @yield('panel')
            </div>
        </div>
    </div>
@endsection
