<section class="mt-10">
    <div class="manufacturer-slider">
        <div class="container">
            <div class="selngHead">
                <h1 class="text-center">Manufacturers</h1>
            </div>
        </div>
        <div id="manufacturerControls" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                @foreach ($manufacturers as $key => $manufacturer)
                    <li data-target="#carouselExampleControls1" data-slide-to="{{$key}}" @if($key==0) class="active" @endif></li>
                @endforeach

            </ol>
            <div class="carousel-inner">
                @foreach ($manufacturers as $key => $manufacturer)
                    <div class="carousel-item text-center @if($key==0) active wow fadeInLeft @endif" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInLeft">
                        <img class="img-fluid w-25" src="{{ manufacturerImage(@$manufacturer->image) }}" alt="First slide" />
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#manufacturerControls" role="button" data-slide="prev">
                <i class="fas fa-angle-left"></i>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#manufacturerControls" role="button" data-slide="next">
                <i class="fas fa-angle-right"></i>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</section>