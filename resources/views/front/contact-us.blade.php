@extends('front.layout.app')
@section('title', 'Contact Us')
@section('content')
    <style>
        .contactPage {
            padding: 70px 180px;
        }
        .cntctHead p {
            font-size: 100%;
            color: #444;
            font-weight: 400;
            margin-bottom: 20px;
        }
        .cntctForm form input {
            width: 100%;
            height: 46px;
            border: solid 1px #bbb;
            background: transparent;
            font-size: 100%;
            font-weight: 500;
            font-style: italic;
            padding: 0 15px;
            margin-bottom: 1.875em;
        }
        .cntctForm form .themeBtn {
            padding: 1em 3em;
            font-weight: 700;
            background-color: #3D3D3D;
            font-size: 0.8125em;
            text-transform: uppercase;
            border-radius: 0;
        }
        .cntctForm form textarea {
            width: 100%;
            height: 46px;
            border: solid 1px #bbb;
            background: transparent;
            font-size: 100%;
            font-weight: 500;
            font-style: italic;
            padding: 15px 15px;
            min-height: 240px;
            margin-bottom: 1.575em;
        }
        .cntctHead p b {
            font-weight: 600;
            color: #000;
        }
        .cntctHead h2 {
            color: #000000;
            font-size: 2.375em;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .cntctForm h2 {
            color: #000000;
            font-size: 2.375em;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .cntctForm button {
            color: #000000;
        }
        @media (max-width: 767.98px){
            .contactPage {
                padding: 30px 0;
            }
        }

    </style>


    <section class="contactPage">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <div class="cntctHead">
                        <h2>Contact Us.</h2>
                        <p><b>Address:</b> Lorem Street, Abc road.</p>
                        <p><b>Email:</b> <a href="mailto:info@yourdomain.com">info@yourdomain.com</a></p>
                        <p><b>Phone:</b> <a href="tel:1234567890">(123) 456-7890</a></p>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="cntctForm">
                        <h2>Get In Touch!</h2>
                        <form method="post" action="{{route('contactUs')}}">
                            @if(Session::has('msg'))
                                <div class="alert alert-success">{{Session::get('msg')}}</div>
                            @endif
                            @csrf
                            <input type="text" placeholder="Name" name="name" required>
                            <input type="email" placeholder="E-mail" name="email" required>
                            <textarea placeholder="Message" name="message" required></textarea>
                            <button class="btn button button-plain" type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13004082.928417291!2d-104.65713107818928!3d37.275578278180674!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited%20States!5e0!3m2!1sen!2s!4v1616490513161!5m2!1sen!2s" width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </section>

@endsection