<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>K7Store Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')}}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ URL::asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ URL::asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ URL::asset('admin/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('admin/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ URL::asset('admin/plugins/toastr/toastr.min.css') }}">
    @yield('page_css')
    <link rel="stylesheet" href="{{ URL::asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ URL::asset('admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ URL::asset('admin/plugins/summernote/summernote-bs4.min.css') }}">


</head>

<body class="login-page" style="min-height: 512.391px;">
    <div class="login-box">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake"
                src="{{ isset($setting->logo) ? URL::asset('uploads/settings/' . $setting->logo) : URL::asset('admin/dist/img/AdminLTELogo.png') }}"
                alt="K7Store" height="60" width="60">
        </div>


        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        {{-- <div class="card-body"> --}}
        {{-- <button type="button" class="btn btn-success toastrDefaultSuccess"> --}}
        {{-- Launch Success Toast --}}
        {{-- </button> --}}
        {{-- </div> --}}
        {{-- <!-- /.content-wrapper --> --}}
        {{-- <footer class="main-footer"> --}}
        {{-- <strong>Copyright &copy; 2021 <a href="#">Ecommerce</a>.</strong> --}}
        {{-- All rights reserved. --}}
        {{-- <div class="float-right d-none d-sm-inline-block"> --}}
        {{-- <b>Version</b> 3.1.0 --}}
        {{-- </div> --}}
        {{-- </footer> --}}

        {{-- <!-- Control Sidebar --> --}}
        {{-- <aside class="control-sidebar control-sidebar-dark"> --}}
        {{-- <!-- Control sidebar content goes here --> --}}
        {{-- </aside> --}}
        {{-- <!-- /.control-sidebar --> --}}
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ URL::asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ URL::asset('admin/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ URL::asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ URL::asset('admin/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ URL::asset('admin/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ URL::asset('admin/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('admin/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ URL::asset('admin/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ URL::asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::asset('admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ URL::asset('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}">
    </script>
    <!-- Summernote -->
    <script src="{{ URL::asset('admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ URL::asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('admin/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ URL::asset('admin/dist/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ URL::asset('admin/dist/js/pages/dashboard.js') }}"></script>

    <script src="{{ URL::asset('admin/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/alert.js') }}"></script>
    <script src="{{ URL::asset('admin/plugins/toastr/toastr.min.js') }}"></script>
    @if (session()->has('success'))
        <script type="text/javascript">
            toastr.success('{{ session('success') }}');
        </script>
    @endif
    @if (session()->has('error'))
        <script type="text/javascript">
            toastr.success('{{ session('error') }}');
        </script>
    @endif
    <script>


    </script>
    @yield('script')
</body>

</html>
