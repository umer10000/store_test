<header>
    <div class="top">
        <div class="container">
            <div class="row">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="{{url('/')}}">
                        <img src="" alt="logo" class="img-fluid">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler"
                        aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarToggler">
                        <ul class="navbar-nav">
                         
                        
                     

                            <li class="  ">
                                <a href="{{url('/')}}">Home</a>
                            </li>
                            <li class="  ">
                                <a href="{{url('/blog')}}">Blog</a>
                            </li>

                            <li class="___class_+?16___">
                                @php $cartItemsCount = \Gloudemans\Shoppingcart\Facades\Cart::count();@endphp
                                <a href="{{ url('cart') }}">
                                    <i class="fal fa-shopping-cart"></i><span class="badge bg-primary">
                                        {{ $cartItemsCount }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                        @guest
                            <div class="header-notifications user-menu">
                                <div class="header-notifications-trigger">
                                    <a href="javascript:void(0)" class="signin">
                                        SIGN IN NOW
                                        <div class="user-avatar off">
                                            <img src="{{ asset('front/images/user-avatar.png') }}" alt="user-avatar">
                                        </div>
                                    </a>
                                </div>

                                <div class="header-notifications-dropdown">
                                    <!-- User Status -->
                                    <div class="user-status text-center">
                                        <a href="{{ url('login') }}"
                                            class="button button-load-more button-sliding-icon ripple-effect full-width">
                                            Sign In
                                            <i class="fas fa-long-arrow-alt-right"></i>
                                        </a>
                                        <div class="margin-top-20"></div>
                                        <a href="{{ url('register') }}"
                                            class="button button-load-more button-sliding-icon ripple-effect full-width">Sign
                                            Up For Free
                                            <i class="fas fa-long-arrow-alt-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="header-notifications user-menu">
                                <div class="header-notifications-trigger">
                                    <a href="javascript:void(0)" class="signin">
                                        Dashboard
                                        @if (Auth::check() && Auth::user()->role_id == 2)
                                            <div class="user-avatar off">
                                                <img src="{{ sellerProfilePicture(@Auth::user()->seller->profile_picture) }}"
                                                    alt="user-avatar">
                                            </div>
                                        @elseif(Auth::check() && Auth::user()->role_id == 3)
                                            <div class="user-avatar off">
                                                <img src="{{ buyerProfilePicture(@Auth::user()->profile_picture) }}"
                                                    alt="user-avatar">
                                            </div>
                                        @elseif(Auth::check() && Auth::user()->role_id == 1)
                                            <div class="user-avatar off">
                                                <img src="{{ buyerProfilePicture(@Auth::user()->profile_picture) }}"
                                                    alt="user-avatar">
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="header-notifications-dropdown">
                                    <!-- User Status -->
                                    <div class="user-status text-center">
                                        @if (Auth::check() && Auth::user()->role_id == 2)
                                            <a href="{{ url('seller/dashboard') }}"
                                                class="button button-load-more button-sliding-icon ripple-effect full-width">
                                                Dashboard
                                                <i class="fas fa-long-arrow-alt-right"></i>
                                            </a>
                                            <div class="margin-top-20"></div>
                                            <a class="button button-load-more button-sliding-icon ripple-effect full-width"
                                                href="{{ route('logout') }}"
                                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                <i class="nav-icon fas fa-lock"></i>
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        @elseif(Auth::check() && Auth::user()->role_id == 3)
                                            <a href="{{ url('seller/dashboard') }}"
                                                class="button button-load-more button-sliding-icon ripple-effect full-width">
                                                Dashboard<i class="fas fa-long-arrow-alt-right"></i>
                                            </a>
                                            <div class="margin-top-20"></div>
                                            <a class="button button-load-more button-sliding-icon ripple-effect full-width"
                                                href="{{ route('logout') }}"
                                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                <i class="nav-icon fas fa-lock"></i> {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        @elseif(Auth::check() && Auth::user()->role_id == 1)
                                            <a href="{{ url('admin/dashboard') }}"
                                                class="button button-load-more button-sliding-icon ripple-effect full-width">
                                                Dashboard<i class="fas fa-long-arrow-alt-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endguest
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="container">
            <div class="row">
                <div class="search d-md-flex">
                    <form class="form-inline" method="Get" action="{{ url('all-products') }}">
                        <button type="submit">
                            <i class="fal fa-search"></i>
                            search
                        </button>
                        <input class="form-control" type="search" name="query" required placeholder="Search Keywords"
                            aria-label="Search" value="{{ request()->has('query') ? request()->get('query') : '' }}"
                            minlength="3">
                    </form>
                    <!--  <a class="cartIo" href="cart.php"><i class="fa fa-shopping-cart"></i><span class="badge">0</span></a> -->
                </div>
            </div>
            <div class="row">
                <nav class="navbar navbar-expand-md">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarTogglerBottom" aria-controls="navbarTogglerBottom" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerBottom">
                        <ul class="navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">Shop by Brand</a>
                                <div class="dropdown-menu">
                                    <ul class="menu">
                                        @forelse($setting['brands'] as $brand)
                                            <li><a
                                                    href="{{ route('all-products.index', ['brand' => $brand->id]) }}">{{ $brand->name }}</a>
                                            </li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </div>
                            </li>
                            @forelse($setting['categories'] as $index => $categories)
                                @if (count($categories->subCategories) > 0)
                                    <li class="dropdown ">
                                        <a href="#" class="dropdown-toggle" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">{{ $categories->name }}
                                        </a>
                                        <div class="dropdown-menu">
                                            <ul class="menu">
                                                @forelse($categories->subCategories as $sub_category)
                                                    <li>
                                                        <a
                                                            href="{{ route('all-products.index', ['category' => $sub_category->id]) }}">{{ $sub_category->name }}</a>
                                                    </li>
                                                @empty
                                                @endforelse
                                            </ul>
                                        </div>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ url('all-products?mainCategory=') . $categories->id }}"
                                            style="font-size: 13px !important">
                                            {{ $categories->name }}
                                        </a>
                                    </li>
                                @endif
                                @empty
                                @endforelse
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
