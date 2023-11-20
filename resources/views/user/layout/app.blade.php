<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Viva Unlimited LLC</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/fontawesome.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/animate.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/jquery.fancybox.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/slick.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/slick-theme.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/custom.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('front/css/responsive.min.css')}}" />
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.css" rel="stylesheet"/>
    <link href="{{asset('front/css/intlTelInput.min.css')}}" rel="stylesheet">

    @yield('page_css')
    @yield('extra-css')
</head>

<body>


<?php $activePage = basename($_SERVER['PHP_SELF'], ".php"); ?>


<header>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-3 col-sm-6">
                <a href="{{url('/')}}" class="logo"><img src="" alt="" class="img-fluid"></a>
            </div>
            <div class="col-md-6 col-sm-6">
                <div id="mySidenav" class="sidenav">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                    <a href="{{route('homepage')}}" class="<?= ($activePage == '' || $activePage=="/" || $activePage=="index") ? 'active':''; ?>">Home</a>
                    <a href="{{route('aboutUs')}}" class="<?= ($activePage == 'about-us') ? 'active':''; ?>">About Us</a>
                    <a href="{{route('shop.index')}}" class="<?= ($activePage == 'shop') ? 'active':''; ?>">Shop</a>
                    <a href="{{route('contactUs')}}" class="<?= ($activePage == 'contact') ? 'active':''; ?>">Contact Us</a>
                </div>
                <!-- Use any element to open the sidenav -->
                <span onclick="openNav()" class="toggleBtn"><i class="fa fa-bars"></i></span>
            </div>
            <div class="col-md-3">
                <ul class="cartOpt">
                    <li><a href="#search"><i class="fal fa-search"></i></a></li>
                    <li><a href="{{route('login')}}"><i class="fal fa-lock"></i></a></li>
                    <li><a href="{{route('cart.index')}}"><i class="fal fa-shopping-cart"></i><span class="badge">0</span></a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

@yield('content')

@include('front.partials.footer')


<!-- jQuery -->
{{--<script src="{{URL::asset('admin/plugins/jquery/jquery.min.js')}}"></script>--}}
{{--<!-- jQuery UI 1.11.4 -->--}}
{{--<script src="{{URL::asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>--}}
{{--<!-- Bootstrap 4 -->--}}
{{--<script src="{{URL::asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}

{{--<script src="{{URL:: asset('admin/sweetalert.min.js') }}"></script>--}}
{{--<script src="{{URL:: asset('admin/alert.js') }}"></script>--}}
<script src="{{URL:: asset('admin/plugins/toastr/toastr.min.js')}}"></script>
<script>
    $(document).ready(function(){
        base_url = "{{ url('/') }}";
    });
</script>


@if(session()->has('success'))
    <script type="text/javascript">  toastr.success('{{ session('success')}}');</script>
@endif
@if(session()->has('error'))
    <script type="text/javascript"> toastr.error('{{ session('error')}}');</script>
@endif

@yield('extra-js')

</body>
</html>
