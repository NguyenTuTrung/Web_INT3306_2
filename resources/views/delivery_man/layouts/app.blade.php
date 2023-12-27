@extends('delivery_man.layouts.master')
@section('content')
    <div class="page-wrapper default-version">
        @include('delivery_man.partials.sidenav')
        @include('delivery_man.partials.topnav')
        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                @include('delivery_man.partials.breadcrumb')
                @yield('panel')
            </div>
        </div>
    </div>
@endsection
