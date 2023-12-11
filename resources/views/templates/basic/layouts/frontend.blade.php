<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $general->sitename(__($pageTitle)) }}</title>
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/line-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/lightbox.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/owl.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/select2.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/main.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/bootstrap-fileinput.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'frontend/css/custom.css')}}">
    <link rel="shortcut icon" href="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" type="@lang('image/x-icon')">
    <link href="{{ asset($activeTemplateTrue . 'frontend/css/color.php') }}?color={{$general->base_color}}" rel="stylesheet"/>
    @stack('style-lib')
    @stack('style')
</head>

<body>
    <div class="overlay"></div>
    <a href="#0" class="scrollToTop"><i class="las la-angle-up"></i></a>
    <div class="preloader">
        <div class="loader"></div>
    </div>
    @include($activeTemplate . 'partials.header')
    @yield('content')
    @include($activeTemplate . 'partials.footer')


    <script src="{{asset($activeTemplateTrue.'frontend/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'frontend/js/bootstrap.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'frontend/js/rafcounter.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'frontend/js/lightbox.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'frontend/js/wow.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'frontend/js/owl.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'frontend/js/viewport.jquery.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'frontend/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'frontend/js/select2.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'frontend/js/main.js')}}"></script>

    @stack('script-lib')
    @stack('script')
    @include('partials.notify')
    <script>
       $('document').ready(function() {
            "use strict";
            $(".langChanage").on("change", function() {
                window.location.href = "{{route('home')}}/change/"+$(this).val() ;
            });
        });
    </script>
</body>

</html>



