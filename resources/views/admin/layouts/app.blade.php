<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <title>AdminLTE 3 | Dashboard</title> --}}
    <title>{{ $setting->company_name }} - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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

    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />

    {{-- table colors remove --}}
    <style>
        table.dataTable thead th,
        table.dataTable thead td {
            border-bottom: 0px;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #dee2e6;
        }

        #example1_filter input {
            border-color: #f2f2f2;
        }

    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake"
                src="{{ isset($setting->logo) ? URL::asset('uploads/settings/' . $setting->logo) : URL::asset('admin/dist/img/AdminLTELogo.png') }}"
                alt="K7Store">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url('admin/dashboard') }}" class="brand-link" style="text-align: center">
                <img class="animation__shake"
                    src="{{ isset($setting->logo) ? URL::asset('uploads/settings/' . $setting->logo) : URL::asset('admin/dist/img/AdminLTELogo.png') }}"
                    alt="Viva Unlimited LLC" style="max-width: 155px;width: 100%;height: auto;">
                {{-- <span class="brand-text font-weight-light">Dashboard</span> --}}
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview"
                        role="menu" data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ url('admin/dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('settings') }}" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Settings
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tags fw"></i>
                                <p>CMS</p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('cms-first-section') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>First Section</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('cms-second-section') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Second Section</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('cms-third-section') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Third Section</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('cms-terms-and-conditions-section') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Terms & Condition</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('home-banner-section') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Home Banner</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('search-banner-section') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Search Banner</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('email-setting') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Email Setting</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tags fw"></i>
                                <p>SHOP</p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin-shop') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Shop Setup</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin-shop-address') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Shop Address</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tags fw"></i>
                                <p>
                                    Catalog
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{-- <li class="nav-item"> --}}
                                {{-- <a href="{{route('catalog.attributeGroups')}}" class="nav-link"> --}}
                                {{-- <i class="nav-icon fa fa-angle-double-right"></i> --}}
                                {{-- <p>Attribute Group</p> --}}
                                {{-- </a> --}}
                                {{-- </li> --}}
                                {{-- <li class="nav-item"> --}}
                                {{-- <a href="{{route('catalog.attributes')}}" class="nav-link"> --}}
                                {{-- <i class="nav-icon fa fa-angle-double-right"></i> --}}
                                {{-- <p>Attributes</p> --}}
                                {{-- </a> --}}
                                {{-- </li> --}}
                                {{-- <li class="nav-item"> --}}
                                {{-- <a href="{{route('catalog.options')}}" class="nav-link"> --}}
                                {{-- <i class="nav-icon fa fa-angle-double-right"></i> --}}
                                {{-- <p>Options</p> --}}
                                {{-- </a> --}}
                                {{-- </li> --}}
                                {{-- <li class="nav-item"> --}}
                                {{-- <a href="{{route('catalog.optionValues')}}" class="nav-link"> --}}
                                {{-- <i class="nav-icon fa fa-angle-double-right"></i> --}}
                                {{-- <p>Option Values</p> --}}
                                {{-- </a> --}}
                                {{-- </li> --}}
                                <li class="nav-item">
                                    <a href="{{ route('category') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Category</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('product.index') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Product</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('manufacturer.index') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Brands</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item"> --}}
                                {{-- <a href="{{route('coupons.index')}}" class="nav-link"> --}}
                                {{-- <i class="nav-icon fa fa-angle-double-right"></i> --}}
                                {{-- <p>Coupons</p> --}}
                                {{-- </a> --}}
                                {{-- </li> --}}
                                <li class="nav-item">
                                    <a href="{{ route('newsletter.index') }}" class="nav-link">
                                        <i class="nav-icon fa fa-angle-double-right"></i>
                                        <p>Newsletter</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item"> --}}
                                {{-- <a href="{{route('shipping.index')}}" class="nav-link"> --}}
                                {{-- <i class="nav-icon fa fa-angle-double-right"></i> --}}
                                {{-- <p>Shipping Rate</p> --}}
                                {{-- </a> --}}
                                {{-- </li> --}}

                            </ul>
                        </li>

                        <li class="nav-item">
                            {{-- {{route('sellers.index')}} --}}
                            <a href="{{ route('sellers.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Sellers</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('buyers.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Buyers</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('order.index') }}" class="nav-link">
                                <i class="nav-icon fa fa-shopping-cart" aria-hidden="true"></i>
                                <p>Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('payments.index') }}" class="nav-link">
                                <i class="fas fa-money-bill-wave"></i>
                                <p>Payments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('deals.index') }}" class="nav-link">
                                <i class="fab fa-dyalog"></i>
                                <p>Special Deals</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('specialDealPackage.index') }}" class="nav-link">
                                <i class="fab fa-dyalog"></i>
                                <p>Special Deals Package</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('featuredAds.index') }}" class="nav-link">
                                <i class="fas fa-ad"></i>
                                <p>Featured Ad's</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('featuredPackage.index') }}" class="nav-link">
                                <i class="fas fa-ad"></i>
                                <p>Featured Package</p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="{{ route('deals.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Featured Product</p>
                            </a>
                        </li> --}}

                        <li class="nav-item">
                            <a href="{{ route('review.index') }}" class="nav-link">
                                <i class="nav-icon fa fa-comments"></i>
                                <p>Reviews</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/changePassword') }}" class="nav-link">
                                <i class="fas fa-key"></i>
                                <p>Change Password</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                <i class="nav-icon fas fa-lock"></i>
                                <p>{{ __('Logout') }}</p>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        @yield('section')
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ $setting->company_name }}</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                {{-- <b>Version</b> 3.1.0 --}}
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
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
    {{-- <!-- ChartJS --> --}}
    {{-- <script src="{{URL::asset('admin/plugins/chart.js/Chart.min.js')}}"></script> --}}
    {{-- <!-- Sparkline --> --}}
    {{-- <script src="{{URL::asset('admin/plugins/sparklines/sparkline.js')}}"></script> --}}
    <!-- JQVMap -->
    {{-- <script src="{{URL::asset('admin/plugins/jqvmap/jquery.vmap.min.js')}}"></script> --}}
    {{-- <script src="{{URL::asset('admin/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script> --}}
    {{-- <!-- jQuery Knob Chart --> --}}
    {{-- <script src="{{URL::asset('admin/plugins/jquery-knob/jquery.knob.min.js')}}"></script> --}}
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>



    @if (session()->has('success'))
        <script type="text/javascript">
            toastr.success('{{ session('success') }}');
        </script>
    @endif
    @if (session()->has('error'))
        <script type="text/javascript">
            toastr.error('{{ session('error') }}');
        </script>
    @endif
    <script>


    </script>
    @yield('script')
</body>

</html>
