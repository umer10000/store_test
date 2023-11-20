@extends('front.layout.app')

@section('title', 'Products')

@section('content')
    <section class="tAndCMain">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {!! isset($termsandconditions->components[0]->description) ? $termsandconditions->components[0]->description :'' !!}
                </div>
            </div>
        </div>
    </section>

@endsection

@section('extra-js')
    {{-- <!-- Include AlgoliaSearch JS Client and autocomplete.js library -->
<script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script> --}}
    {{-- <script src="{{ asset('js/algolia.js') }}"></script> --}}
@endsection
