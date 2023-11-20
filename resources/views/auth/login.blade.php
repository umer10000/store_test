@extends('front.layout.app')
@section('title', 'Login')
@section('content')

    <section class="login-plane-sec main-signinpg">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 padding-bottom-50 text-center">
                
                </div>
                <div class="col-md-6 offset-md-3">
                    <div class="login-panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">We're glad to see you again!</h3>
                            <p>Don't have an account? <a href="{{ url('register') }}">Sign Up For Free!</a></p>
                        </div>
                        <div class="panel-body">
                            @if (session()->has('success_message'))
                                <div class="alert alert-success">
                                    {{ session()->get('success_message') }}
                                </div>
                            @endif
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form class="px-lg-4 ajax-form form-cadastro" action="{{ route('login') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="tabbable-panel">
                                    <div class="tabbable-line">
                                        <div class="tab-content register-tab sign-tab">
                                            <div class="tab-pane active" id="tab_default_1">
                                                <div class="col-sm-12">
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        value="{{ old('email') }}" placeholder="Email" required autofocus>

                                                    <input type="password" id="password" class="form-control"
                                                        name="password" value="{{ old('password') }}"
                                                        placeholder="Password" required>

                                                    <div class="java-ar">
                                                        <a href="javascript:void(0);" id="show"><i data-input="pass-sign-in"
                                                                class="fa fa-eye field-icon show-pass"></i></a>
                                                    </div>
                                                    <button type="submit" style="border: none"
                                                        class="orangeBtn w-100 d-block text-center text-capitalize">
                                                        Sign In
                                                    </button>
                                                    <div class="para-ar">
                                                        {{-- <span>or</span> --}}
                                                    </div>
                                                    <div class="java-ar">
                                                        <a href="{{ url('password/reset') }}" class="toggle-login">Forgot
                                                            your password?</a>
                                                    </div>
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
        $('#show').on('click', function() {
            if ($('#password').attr('type') == "password") {
                $('#password').attr('type', 'text');
            } else {
                $('#password').attr('type', 'password');
            }
        });
    </script>
@endsection
