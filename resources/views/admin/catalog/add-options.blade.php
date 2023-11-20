@extends('admin.layouts.app')
@section('title',  isset($option->id) ? 'Edit Option' : 'Add Options')
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
    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Option Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Option Form</li>
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
                                <h3 class="card-title">{{isset($option->id) ? 'Edit' : 'Add'}} Options</h3>
                            </div>

                            <form class="option-form" method="post"
                                  action="{{ isset($option->id) ? route('catalog.editOption', $option->id) : route('catalog.addOption')}}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif
                                    <label for="name" class="ml-2">Option Name</label>

                                    @if(!empty(old('option_name')))
                                        @foreach(old('option_name') as $oldOptionName)
                                            <div class="row col-md-12">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control @error('option_name') is-invalid @enderror" name="option_name[]" id="option_name" value="{{$option->option_name ?? $oldOptionName}}" placeholder="Name" required>
                                                        @error('option_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mt-1">
                                                    @if(!isset($option->id))
                                                        <span class="add-field"><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                                                        <span class="remove-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row col-md-12">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <input type="text" class="form-control @error('option_name') is-invalid @enderror" name="option_name[]" id="option_name" value="{{$option->option_name ?? ''}}" placeholder="Name" required>
                                                    @error('option_name')
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2 mt-1">
                                                @if(!isset($option->id))
                                                    <span class="add-field"><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                                                    <span class="remove-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($option->id))
                                        <div class="row">
                                            <div class="col ml-2">
                                                <label for="" class="mr-4">Status</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="status" @if($option->status == 1) checked @endif data-id="" id="status-switch">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('catalog.options')}}" class="btn btn-warning btn-md">Cancel</a>
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
            $(document).on('click', ".add-field" ,function() {
                var lastField = $(".option-form .card-body .row:last");
                var html = '<div class="row col-md-12"> <div class="col-md-10"> <div class="form-group"> <input type="text" class="form-control" name="option_name[]" id="option_name" value="" placeholder="Name" required> </div></div><div class="col-md-2 mt-1"><span class="add-field"><i class="fa fa-plus-circle" aria-hidden="true"></i></span> <span class="remove-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></div></div>';
                lastField.parent().append(html);
            });

            $(document).on('click', ".remove-field" ,function() {
                var fields = $(".option-form .card-body .row");
                var isLast = fields.length;

                if (isLast == 1){
                    toastr.error('Cant delete. One Option Name is required')
                }else{
                    $(this).parent().parent().remove();
                }

            });
        });

    </script>
@endsection
