@extends('staff_warehouse.layouts.master')
@section('content')
    <div class="page-wrapper default-version">
        @include('staff_warehouse.partials.sidenav')
        @include('staff_warehouse.partials.topnav')
        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                @include('staff_warehouse.partials.breadcrumb')
                @yield('panel')
            </div>
        </div>
    </div>
@endsection
