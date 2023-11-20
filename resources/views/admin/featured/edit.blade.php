@extends('admin.layouts.app')
@section('title', 'Add Featured Ad')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Featured Ad Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Featured Ad Edit Form</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-8">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Featured Ad Edit Form</h3>
                            </div>
                            <form class="category-form" method="post"
                                action="{{ url('admin/featuredAdsUpdate/' . $FeaturedAd->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (Session::has('msg'))
                                        <div class="alert alert-success">{{ Session::get('msg') }}</div>
                                    @endif
                                    <div class="form-group">
                                        <label for="exampleInputFile">Banner(Dimensions: 790x439)</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="banner" id="banner_file"
                                                    onchange="PreviewImage('banner_file','banner')"
                                                    accept="image/png, image/jpg, image/jpeg">
                                                <label class="custom-file-label" for="category-image">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <img id="banner"
                                            src="{{ asset(isset($FeaturedAd->banner) ? 'uploads/featured_ads/' . $FeaturedAd->banner : 'admin/images/placeholder.png') }}"
                                            alt="" style="height: 300px;width: 600px;">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Start Date</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" name="start_date" id="start_date"
                                                value="{{ $FeaturedAd->start_date }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">End Date</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" name="end_date" id="end_date"
                                                value="{{ $FeaturedAd->end_date }}" required>
                                        </div>
                                    </div>


                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{ route('featuredAds.index') }}" class="btn btn-warning btn-md">Cancel</a>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var _URL = window.URL || window.webkitURL;

            $('input[name="banner"]').change(function(e) {
                var file, img;
                if ((file = this.files[0])) {
                    img = new Image();
                    img.onload = function() {
                        // alert(this.width + " " + this.height);
                        if (this.width !== 790) {
                            alert('Banner Width not Matched!');
                            $('input[name="banner"]').val('');
                            $('#banner').attr('src', '/admin/images/placeholder.png');
                        }
                        if (this.height !== 439) {
                            alert('Banner Height not Matched!');
                            $('input[name="banner"]').val('');
                            $('#banner').attr('src', '/admin/images/placeholder.png');
                            // return;
                        }
                        return;
                    };
                    img.onerror = function() {
                        alert("not a valid file: " + file.type);
                        $('input[name="banner"]').val('');
                        $('#banner').attr('src', '/admin/images/placeholder.png');
                        return;
                    };
                    img.src = _URL.createObjectURL(file);
                }
            });

            $("#end_date").change(function() {
                var startDate = document.getElementById("start_date").value;
                var endDate = document.getElementById("end_date").value;

                if ((Date.parse(startDate) >= Date.parse(endDate))) {
                    alert("End date should be greater than Start date");
                    document.getElementById("end_date").value = "";
                }
            });
        });

        function PreviewImage(inputId, id) {

            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById(inputId).files[0]);

            oFReader.onload = function(oFREvent) {
                document.getElementById(id).src = oFREvent.target.result;
            };
        }

        // MARK AS FEATURED BANNER
    </script>
@endsection
