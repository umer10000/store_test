@extends('front.layout.app')
@section('title', 'Checkout Success')
@section('content')

    <div class="thank-you-section text-center">
        @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul class="p-0 m-0" style="list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <br>
        <h1>Thank you for <br> Your Order!</h1>
        <p>A confirmation email was sent</p>
        @isset($path)
            <a href="{{ $path }}" target="_blank" class="btn btn-primary btn-md">Download Now <i class="fa fa-download"
                    aria-hidden="true"></i></a>
            @endif
            <div class="spacer"></div>
            <div>
                @if (Session::has('success'))
                    {{ Session::get('success') }}
                @endif
                <br>
                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif
                <br>
                <a href="{{ url('/') }}" class="button">Home Page</a>
            </div>
        </div><br>

    @endsection
