<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>K7 Tracks Store | @yield('title', '')</title>


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

{{--    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">--}}

<!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{URL::asset('front/css/bootstrap.min.css')}}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{URL::asset('front/css/fontawesome.min.css')}}">

    <!-- Animate style -->
    <link rel="stylesheet" href="{{URL::asset('front/css/animate.min.css')}}">

    <link rel="stylesheet" href="{{URL::asset('front/css/jquery.fancybox.min.css')}}"/>

    <link rel="stylesheet" href="{{URL::asset('front/css/slick.min.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('front/css/slick-theme.min.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('front/css/multi-select.css')}}"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css"
          rel="stylesheet"/>
    <link rel="stylesheet" href="{{URL::asset('front/css/custom.min.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('front/css/responsive.css')}}"/>


    @yield('page_css')
    {{--    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">--}}
    <link href="{{asset('front/css/intlTelInput.min.css')}}" rel="stylesheet">
    <!-- toastr -->
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/toastr/toastr.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{URL::asset('admin/plugins/summernote/summernote-bs4.min.css')}}">

    @yield('extra-css')
</head>


<body>

@include('front.partials.nav')

@yield('content')



{{--<!-- jQuery -->--}}
{{--<script src="{{URL::asset('admin/plugins/jquery/jquery.min.js')}}"></script>--}}
{{--<!-- jQuery UI 1.11.4 -->--}}
{{--<script src="{{URL::asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>--}}
{{--<!-- Bootstrap 4 -->--}}
{{--<script src="{{URL::asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}

{{--<script src="{{URL:: asset('admin/sweetalert.min.js') }}"></script>--}}
{{--<script src="{{URL:: asset('admin/alert.js') }}"></script>--}}

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{URL::asset('front/js/jquery-3.5.1.min.js')}}"></script>
<script src="{{URL::asset('front/js/popper.min.js')}}"></script>
<script src="{{URL::asset('front/js/bootstrap.min.js')}}"></script>
<script src="{{URL::asset('front/js/jquery.fancybox.min.js')}}"></script>
<script src="{{URL::asset('front/js/slick.min.js')}}"></script>
<script src="{{URL::asset('front/js/wow.min.js')}}"></script>
<script src="{{URL::asset('front/js/multiple-select.js')}}"></script>
<script src="{{URL::asset('front/js/select2.min.js')}}"></script>
<script src="{{URL::asset('front/js/bootstrap-inputmask.min.js')}}"></script>
<script src="{{URL::asset('front/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{URL::asset('front/js/jquery.multi-select.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="{{URL::asset('front/js/custom.min.js')}}"></script>

<script src="{{URL:: asset('admin/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('front/js/intlTelInput-jquery.min.js')}}"></script>

@if(session()->has('success'))
    <script type="text/javascript">  toastr.success('{{ session('success')}}');</script>
@endif
@if(session()->has('error'))
    <script type="text/javascript"> toastr.error('{{ session('error')}}');</script>
@endif

@include('front.partials.footer')

@yield('extra-js')


</body>
</html>
