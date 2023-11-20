@extends('admin.layouts.app')
@section('title', 'Edit Review')
@section('section')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
        .star {
            color: #f7941d !important;
        }

        .reviewForm>div>div>div>span>i.hover{
            color: rgb(255, 192, 87) !important;
        }
    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Review Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Review Form</li>
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
                    <div class="col-md-9">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Review</h3>
                            </div>
                            <form class="reviewForm" method="post"
                                  action="{{route('review.edit', $product_review->id)}}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-10">
                                            <label for="product_name">Product Name*</label>
                                            <input type="text" class="form-control" name="product_name" id="product_name" value="{{$product_review->product->product_name ?? ''}}" placeholder="Product Name" required>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-10">
                                            <label for="author">Author*</label>
                                            <input type="text" class="form-control" name="author" id="author" value="{{$product_review->author ?? ''}}" placeholder="Author" required>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-10">
                                            <label for="description">Text*</label>
                                            <textarea class="form-control" name="description" id="description" style="height: 160px;" required>{{$product_review->description ?? ''}}</textarea>
                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-10">
                                            <label for="rating">Rating*</label>
                                            <span>
                                                @for($i=1; $i<= $product_review->rating; $i++)
                                                    <i data-value="{{$i}}" class="fas fa-star star"></i>
                                                @endfor
                                                @for($j=1; $j<= 5-$product_review->rating; $j++)
                                                    <i data-value="{{$product_review->rating+$j}}" class="fas fa-star"></i>
                                                @endfor
                                            </span>
                                            <input type="hidden" name="rating" id="rating" value="{{$product_review->rating ?? ''}}">
                                        </div>
                                    </div>
                                    @if(isset($product_review->id))
                                        <div class="row">
                                            <div class="col ml-2">
                                                <label for="" class="mr-4">Status</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="status" @if($product_review->status == 1) checked @endif data-id="" id="status-switch">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('review.index')}}" class="btn btn-warning btn-md">Cancel</a>
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
        $(document).ready(function (){
            /* 1. Visualizing things on Hover - See next part for action on click */
            $('.reviewForm span i').on('mouseover', function(){ console.log('asdf')
                var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
                // Now highlight all the stars that's not after the current hovered star
                $(this).parent().children('i').each(function(e){
                    if (e < onStar) {
                        $(this).addClass('hover');
                    }
                    else {
                        $(this).removeClass('hover');
                    }
                });

            }).on('mouseout', function(){
                $(this).parent().children('i').each(function(e){
                    $(this).removeClass('hover');
                });
            });


            $('.reviewForm span i').on('click', function(){
                var onStar = parseInt($(this).data('value'), 10); // The star currently selected
                //console.log(onStar)
                var stars = $(this).parent().children('i');
                for (i = 0; i < stars.length; i++) {
                    $(stars[i]).removeClass('star');
                }

                for (i = 0; i < onStar; i++) {
                    $(stars[i]).addClass('star');
                    $('#rating').val(parseInt(onStar));
                }
            });
        })
    </script>
@endsection
