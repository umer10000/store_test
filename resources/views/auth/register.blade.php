@extends('front.layout.app')

@section('title', 'Sign Up for an Account')

@section('content')
    {{-- <div class="container"> --}}
    {{-- <div class="auth-pages"> --}}
    {{-- <div> --}}
    {{-- @if (session()->has('success_message')) --}}
    {{-- <div class="alert alert-success"> --}}
    {{-- {{ session()->get('success_message') }} --}}
    {{-- </div> --}}
    {{-- @endif @if (count($errors) > 0) --}}
    {{-- <div class="alert alert-danger"> --}}
    {{-- <ul> --}}
    {{-- @foreach ($errors->all() as $error) --}}
    {{-- <li>{{ $error }}</li> --}}
    {{-- @endforeach --}}
    {{-- </ul> --}}
    {{-- </div> --}}
    {{-- @endif --}}
    {{-- <h2>Create Account</h2> --}}
    {{-- <div class="spacer"></div> --}}

    {{-- <form method="POST" action="{{ route('register') }}"> --}}
    {{-- {{ csrf_field() }} --}}

    {{-- <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="First Name" required autofocus> --}}

    {{-- <input id="name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required autofocus> --}}

    {{-- <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required> --}}

    {{-- <input id="password" type="password" class="form-control" name="password" placeholder="Password" placeholder="Password" required> --}}

    {{-- <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" --}}
    {{-- required> --}}

    {{-- <div class="login-container"> --}}
    {{-- <button type="submit" class="auth-button">Create Account</button> --}}
    {{-- <div class="already-have-container"> --}}
    {{-- <p><strong>Already have an account?</strong></p> --}}
    {{-- <a href="{{ route('login') }}">Login</a> --}}
    {{-- </div> --}}
    {{-- </div> --}}

    {{-- </form> --}}
    {{-- </div> --}}

    {{-- <div class="auth-right"> --}}
    {{-- <h2>New Customer</h2> --}}
    {{-- <div class="spacer"></div> --}}
    {{-- <p><strong>Save time now.</strong></p> --}}
    {{-- <p>Creating an account will allow you to checkout faster in the future, have easy access to order history and customize your experience to suit your preferences.</p> --}}

    {{-- &nbsp; --}}
    {{-- <div class="spacer"></div> --}}
    {{-- <p><strong>Loyalty Program</strong></p> --}}
    {{-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt debitis, amet magnam accusamus nisi distinctio eveniet ullam. Facere, cumque architecto.</p> --}}
    {{-- </div> --}}
    {{-- </div> <!-- end auth-pages --> --}}
    {{-- </div> --}}

    <section class="login-plane-sec main-signinpg">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 padding-bottom-50 text-center">
                   
                </div>
                <div class="col-md-6 offset-md-3">
                    <div class="login-panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">We're glad to see you again!</h3>
                            <p>Already have an account?
                                <a href="{{ url('login') }}">Sign In!</a>
                            </p>
                        </div>
                        <div class="panel-body">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form class="px-lg-3 ajax-form form-cadastro" method="POST" action="{{ route('register') }}">
                                {{ csrf_field() }}
                                <div class="tabbable-panel">
                                    <div class="tabbable-line">
                                        <ul class="nav nav-pills" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="home-tab" data-toggle="tab"
                                                    href="#tab_default_1" role="tab" aria-controls="home"
                                                    aria-selected="true">Buyer</a>

                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab_default_2"
                                                    role="tab" aria-controls="profile" aria-selected="false">Seller</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content register-tab">
                                            <div class="tab-pane active" id="tab_default_1">
                                                <div class="col-sm-12">

                                                    <input id="name" type="text" class="form-control" name="name"
                                                        value="{{ old('name') }}" placeholder="First Name" required
                                                        autofocus>

                                                    <input id="email" type="email" class="form-control" name="email"
                                                        value="{{ old('email') }}" placeholder="Email" required>

                                                    <input type="tel" class="form-control" name="phone_number"
                                                        id="phone_number" placeholder="Phone Number" required />
                                                    <input type="number" class="form-control" name="ZipCode" id="ZipCode"
                                                        placeholder="Zip Code" required />
                                                    <input id="password" type="password" class="form-control"
                                                        name="password" placeholder="Password" placeholder="Password"
                                                        required>

                                                    <input id="password-confirm" type="password" class="form-control"
                                                        name="password_confirmation" placeholder="Confirm Password"
                                                        required>
                                                    <input type="hidden" name="account_type" id="buyer_seller" value="3">
                                                </div>

                                                <div class="col-sm-12 radio">
                                                    <input id="term-of-accept" name="term-of-accept" type="radio"
                                                        required="" value="yes" />
                                                    <label for="term-of-accept">
                                                        <span class="radio-label"></span>
                                                        I have read and agree to the
                                                        <a href="{{ url('terms-conditions') }}" class="tenant">
                                                            Terms and Conditions
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <button type="submit" style="border: none"
                                                        class="orangeBtn w-100 d-block text-center text-capitalize">Register</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('extra-js')
    <script>
        $('document').ready(function() {

            $('#home-tab').click(function() {
                $('#buyer_seller').val(3);
            });

            $('#profile-tab').click(function() {
                $('#buyer_seller').val(2);
            });
        });
    </script>
@endsection
