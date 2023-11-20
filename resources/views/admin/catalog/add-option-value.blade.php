@extends('admin.layouts.app')
@section('title', isset($optionValue->id) ? 'Edit Option Value' : 'Add Option Values')
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
                        <h1>Option Value Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Option Value Form</li>
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
                                <h3 class="card-title">{{isset($optionValue->id) ? 'Edit' : 'Add'}} Option Value</h3>
                            </div>
                            <form class="option-value-form" method="post"
                                  action="{{ isset($optionValue->id) ? route('catalog.editOptionValue', $optionValue->id) : route('catalog.addOptionValue')}}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-10">
                                            <label for="name">Select Option Name</label>
                                            <select id="option-name" name="option-name" class="form-control" required>
                                                <option value="0">Select Option </option>
                                                @foreach($options as $option)
                                                    <option value="{{$option->id}}" @if(!empty($optionValue) && ($optionValue->option_id == $option->id)) selected @endif>{{$option->option_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <label for="name" class="ml-2">Option Value</label>
                                    <div class="row col-md-12">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="option-value[]" id="option-value" value="{{$optionValue->option_value ?? ''}}" placeholder="Option Value" required>
                                            </div>
                                        </div>
                                        @if(!isset($optionValue->id))
                                        <div class="col-md-2 mt-1">
                                            <span class="add-field"><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
                                            <span class="remove-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span>
                                        </div>
                                        @endif
                                    </div>
                                    @if(isset($optionValue->id))
                                        <div class="row">
                                            <div class="col ml-2">
                                                <label for="" class="mr-4">Status</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="status" @if($optionValue->status == 1) checked @endif data-id="" id="status-switch">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" id="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('catalog.optionValues')}}" class="btn btn-warning btn-md">Cancel</a>
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
                var lastField = $(".option-value-form .card-body .row:last");
                console.log(lastField);
                var html = '<div class="row col-md-12"> <div class="col-md-10"> <div class="form-group">'
                    +'<input type="text" class="form-control" name="option-value[]" id="option-value" value="" placeholder="Option Value" required></div></div>'
                    +'<div class="col-md-2 mt-1"><span class="add-field"><i class="fa fa-plus-circle" aria-hidden="true"></i></span> <span class="remove-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></div></div>';

                lastField.parent().append(html);
            });

            $(document).on('click', ".remove-field" ,function() {
                var fields = $(".option-value-form .card-body .row");
                var isLast = fields.length;
                console.log(isLast);
                if (isLast == 2){
                    toastr.error('Cant delete. One Option Value is required..')
                }else{
                    $(this).parent().parent().remove();
                }

            });
        });
        $('.option-value-form').on('submit', function (){
            var optionId = $("#option-name").val();
            if (optionId == 0){
                toastr.warning('Please Select Option Name')
                return false;
            }
        });

    </script>
@endsection
