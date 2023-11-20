@extends('admin.layouts.app')
@section('title', 'Add Product')
@section('section')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
    <link rel="stylesheet" href="{{ asset('admin/dropzone/dist/basic.css') }}">
    <style>
        .switch {
            position: relative;
            display: block;
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

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
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

        .help-block {
            color: red;
        }

        .has-error {
            border-block-color: red;
        }

    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Product Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Product Form</li>
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
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                {{-- <h3 class="card-title">Product</h3> --}}
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="#product" role="tab"
                                            data-toggle="tab">General</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#additionalImages" role="tab"
                                            data-toggle="tab">Additional Images</a>
                                    </li>
                                    {{-- <li class="nav-item">
                                        <a class="nav-link" href="#attributes" role="tab" data-toggle="tab">Attributes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#options" role="tab" data-toggle="tab">Options</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#related_products" role="tab" data-toggle="tab">Related Products</a>
                                    </li> --}}
                                </ul>
                            </div>
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
                            <form class="category-form" method="post" action="{{ route('product.store') }}"
                                enctype="multipart/form-data">
                                <div class="tab-content ">
                                    <div class="tab-pane active" role="tabpanel" class="tab-pane fade in active"
                                        id="product">

                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Category*</label>
                                                    <select
                                                        class="form-control {{ $errors->has('main_category') ? 'has-error' : '' }}"
                                                        name="main_category" id="main-category" required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($mainCategories as $category)
                                                            <option value="{{ $category->id }}" @if (old('main_category') == $category->id) selected @endif>
                                                                {{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    {!! $errors->first('main_category', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Sub Category</label>
                                                    <select class="form-control" name="sub_category" id="sub-category">
                                                        @forelse ($subCategories as $subcategory)
                                                            <option value="{{ $subcategory->id }}"
                                                                @if (old('sub_category') == $subcategory->id) selected @endif>
                                                                {{ $subcategory->name }}</option>
                                                        @empty
                                                            <option value="0">Select Sub Category</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Brand*</label>
                                                    <select
                                                        class="form-control {{ $errors->has('manufacturer') ? 'has-error' : '' }}"
                                                        name="manufacturer" id="manufacturer" required>
                                                        <option value="">Select Manufacturer</option>
                                                        @foreach ($manufacturers as $manufacturer)
                                                            <option value="{{ $manufacturer->id }}"
                                                                @if (old('manufacturer') == $manufacturer->id) selected @endif>
                                                                {{ $manufacturer->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Product Type</label>
                                                    {{-- <select name="product_featured" id="" class="form-control">
                                                        <option value="Feature" @if ($product->product_type == 'Featured') selected @endif>Featured</option>
                                                        <option value="New" @if ($product->product_type == 'New') selected @endif>New</option>
                                                    </select> --}}
                                                    <select
                                                        class="form-control {{ $errors->has('product_type') ? 'has-error' : '' }}"
                                                        name="product_type" required id="product_type">
                                                        <option value="">Product Type</option>
                                                        <option value="Physical" @if (old('product_type') == 'Physical') selected @endif>Physical</option>
                                                        <option value="Downloadable" @if (old('product_type') == 'Downloadable') selected @endif>Downloadable
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Product Name*</label>
                                                    <input type="text" name="product_name" placeholder="Product Name"
                                                        class="form-control {{ $errors->has('product_name') ? 'has-error' : '' }}"
                                                        value="{{ old('product_name') }}" required>
                                                    {!! $errors->first('product_name', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Quantity*</label>
                                                    <input type="number" name="quantity" placeholder="Quantity"
                                                        class="form-control {{ $errors->has('quantity') ? 'has-error' : '' }}"
                                                        value="{{ old('quantity') }}" required>
                                                    {!! $errors->first('quantity', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Price*</label>
                                                    <input type="number" name="current_price" placeholder="Price"
                                                        class="form-control {{ $errors->has('current_price') ? 'has-error' : '' }}"
                                                        value="{{ old('current_price') }}" required>
                                                    {!! $errors->first('current_price', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Discounted Price*</label>
                                                    <input type="number" name="discounted_price"
                                                        placeholder="Discounted Price"
                                                        class="form-control {{ $errors->has('discounted_price') ? 'has-error' : '' }}"
                                                        value="{{ old('discounted_price') }}" required>
                                                    {!! $errors->first('discounted_price', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>

                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Shipping*</label>
                                                    <select
                                                        class="form-control {{ $errors->has('shipping') ? 'has-error' : '' }}"
                                                        name="shipping" required>
                                                        <option value="">Select Shipping</option>
                                                        <option value="1" @if (old('shipping') == '1') selected @endif>Fedex</option>
                                                        <option value="2" @if (old('shipping') == '2') selected @endif>Free Shipping</option>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Condition</label>
                                                    <select
                                                        class="form-control {{ $errors->has('condition') ? 'has-error' : '' }}"
                                                        name="condition" required>
                                                        <option value="">Condition</option>
                                                        <option value="new" @if (old('condition') == 'new') selected @endif>New</option>
                                                        <option value="used" @if (old('condition') == 'used') selected @endif>Used</option>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Location</label>
                                                    <select
                                                        class="form-control {{ $errors->has('location') ? 'has-error' : '' }}"
                                                        name="location" id="locations" required>
                                                        <option value="">Select Location</option>
                                                        @forelse($countries as $country)
                                                            <option value="{{ $country->id }}" @if (old('location') == $country->id) selected @endif>
                                                                {{ $country->name }}
                                                            </option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </div>

                                                <div class="col">
                                                    <label for="switch">Status</label>
                                                    <label class="switch"><input type="checkbox" data-id=""
                                                            id="status-switch" name="status" value="1"
                                                            @if (old('status') == 1) checked @endif>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Length</label>
                                                    <input type="number" step="any" name="length" placeholder="Length"
                                                        class="form-control" id="length" value="{{ old('length') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Width</label>
                                                    <input type="number" step="any" name="width" class="form-control"
                                                        placeholder="Width" id="width" value="{{ old('width') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Height</label>
                                                    <input type="number" step="any" name="height" class="form-control"
                                                        id="height" placeholder="Height" value="{{ old('height') }}">
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Weight</label>
                                                    <input type="number" step="any" name="weight" class="form-control"
                                                        id="weight" value="{{ old('weight') }}" placeholder="Weight">
                                                </div>

                                                <div class="col" id="product_file_area"
                                                    @if (old('product_type') == 'Downloadable') style="display: block" @else style="display: none" @endif>
                                                    <label for="exampleInputEmail1">Product File</label>
                                                    <input type="file" name="product_file" class="form-control"
                                                        id="product_file" placeholder="Upload Product File"
                                                        title="Product File" accept=".zip">
                                                </div>

                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="category">Description</label>
                                                    <textarea
                                                        class="form-control {{ $errors->has('main_category') ? 'has-error' : '' }}"
                                                        name="description" id="description" placeholder="Description"
                                                        required>{{ old('description') }}</textarea>
                                                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <table class="table">
                                                    <tr>
                                                        <th>Product Image</th>
                                                        <th>Select Image</th>
                                                    </tr>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <img src="{{ asset('admin/images/placeholder.png') }}"
                                                                    alt="" id="img_0" style="height: 150px;width: 150px;">
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input"
                                                                            name="product_image_first" id="gallery_0"
                                                                            onchange="PreviewImage('0')" accept="image/*"
                                                                            required>
                                                                        <label class="custom-file-label"
                                                                            for="category-image">Choose file</label>
                                                                    </div>
                                                                    {!! $errors->first('product_image_first', '<p class="help-block">:message</p>') !!}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <br>

                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="gallery"></div>
                                        </div>

                                        <!-- /.card-body -->
                                    </div>
                                    <div class="tab-pane" role="tabpanel" class="tab-pane fade in active"
                                        id="additionalImages">
                                        <div class="col-md-12 text-right">
                                        </div>
                                        <table class="table">
                                            <tr>
                                                <th>Product Image</th>
                                                <th>Select Image</th>
                                            </tr>
                                            <tbody id="add_more">
                                                <tr>
                                                    <td class="col-md-2">
                                                        <img src="{{ asset('admin/images/placeholder.png') }}" alt=""
                                                            id="img_1" style="height: 150px;width: 150px;">
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input"
                                                                    name="product_image[]" id="gallery_1"
                                                                    onchange="PreviewImage('1')" accept="image/*">
                                                                <label class="custom-file-label" for="category-image">Choose
                                                                    file</label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <input type="button" class="btn btn-md btn-primary" id="addMoreBtn"
                                                            value="+" onclick="addMorePictures(1)" />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" role="tabpanel" class="tab-pane fade in active"
                                        id="attributes">
                                        <div class="col-md-12 text-right">
                                            <input type="button" class="btn btn-primary btn-sm" value="Add Attribute"
                                                onclick="addMoreAttributes()" style="margin-top: 10px;margin-bottom: 10px;">
                                        </div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Attribute</th>
                                                    <th>Value</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="add_more_attr">
                                                <tr>
                                                    {{-- <td> --}}
                                                    {{-- <select id="dino-select" class="form-control" name="attribute[]" required> --}}
                                                    {{-- <option value="">Select Attribute</option> --}}
                                                    {{-- @foreach ($attributeGroups as $attributeGroup) --}}
                                                    {{-- <optgroup label="{{$attributeGroup->attribute_group}}"> --}}
                                                    {{-- @foreach ($attributeGroup->attributes as $attributes) --}}
                                                    {{-- <option value="{{$attributes->id}}">{{$attributes->attribute_name}}</option> --}}
                                                    {{-- @endforeach --}}
                                                    {{-- </optgroup> --}}
                                                    {{-- @endforeach --}}
                                                    {{-- </select> --}}
                                                    {{-- </td> --}}
                                                    {{-- <td><input type="text" class="form-control" value="" name="attribute_value[]" required></td> --}}
                                                    {{-- <td></td> --}}
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" role="tabpanel" class="tab-pane fade in active"
                                        id="options">
                                        <div class="col-md-12 text-right">
                                            <input type="button" class="btn btn-primary btn-sm" value="Add Options"
                                                onclick="addMoreOptions()" style="margin-top: 10px;margin-bottom: 10px;">
                                        </div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Option</th>
                                                    <th>Option Value</th>
                                                    <th>Price</th>
                                                    <th>Qty</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="add_more_option">
                                                {{-- <tr> --}}
                                                {{-- <td> --}}
                                                {{-- <select id="option_id" class="form-control" name="option_id[]" onchange="getOptionValues(1,this.value)"> --}}
                                                {{-- <option value="">Select Option</option> --}}
                                                {{-- @foreach ($options as $option) --}}
                                                {{-- <option value="{{$option->id}}">{{$option->option_name}}</option> --}}
                                                {{-- @endforeach --}}
                                                {{-- </select> --}}
                                                {{-- </td> --}}
                                                {{-- <td> --}}
                                                {{-- <select id="option_value_id_1" class="form-control" name="option_value_id[]"></select> --}}
                                                {{-- </td> --}}
                                                {{-- <td><input type="number" class="form-control" value="" name="option_value_price[]"></td> --}}
                                                {{-- <td><input type="number" class="form-control" value="" name="option_value_qty[]"></td> --}}
                                                {{-- <td></td> --}}
                                                {{-- </tr> --}}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" role="tabpanel" class="tab-pane fade in active"
                                        id="related_products">
                                        <div class="col-md-12 text-right">
                                            <input type="button" class="btn btn-primary btn-sm" value="Add Related Product"
                                                onclick="addMoreRelatedProducts()"
                                                style="margin-top: 10px;margin-bottom: 10px;">
                                        </div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    {{-- <th>Product ID</th> --}}
                                                    <th>Product Name</th>
                                                    {{-- <th>Price</th> --}}
                                                    {{-- <th>Qty</th> --}}
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="add_more_related">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary" id="submit_btn" style="">Submit</button>
                                    <a href="{{ route('product.index') }}" class="btn btn-warning" id=""
                                        style="">Cancel</a>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
        </section>
    </div>
    </div>
@endsection
@section('script')

    <script src="{{ asset('admin/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/dropzone/dist/dropzone.js') }}"></script>
    <script type="text/javascript">
        // window.onload = function() {
        //     CKEDITOR.replace('description', {
        //         {{-- filebrowserUploadUrl: '{{ route('project.document-image-upload',['_token' => csrf_token() ]) }}', --}}
        //         {{-- filebrowserUploadMethod: 'form' --}}
        //     });
        // };


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //Dependent Category
        $(document).ready(function() {
            $('#main-category').on('change', function(e) {
                var cat_id = e.target.value;
                $.ajax({
                    url: "{{ route('getSubCategories') }}",
                    type: "Get",
                    data: {
                        cat_id: cat_id
                    },
                    success: function(data) {
                        $('#sub-category').empty();
                        if (data.subcategories.length > 0) {
                            // console.log(data.subcategories);

                            $('#sub-category').append(
                                '<option value="">Select Sub Category</option>');
                            $.each(data.subcategories, function(index, subcategory) {

                                $('#sub-category').append('<option value="' +
                                    subcategory
                                    .id + '">' + subcategory.name + '</option>');
                            })
                        } else {
                            $('#sub-category').append(
                                '<option value="0">Select Sub Category</option>');
                            $('#sub-category').attr("style", "pointer-events: none;");
                        }
                    }
                })
            });



        });

        var counter = 1;

        function addMorePictures() {
            counter++;
            $('#add_more').append(`<tr id="row_${counter}">
                                        <td class="col-md-2">
                                            <img src="{{ asset('admin/images/placeholder.png') }}" alt="" id="img_${counter}" style="height: 150px;width: 150px;">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="product_image[]"  id="gallery_${counter}" onchange="PreviewImage('${counter}')" accept="image/*">
                                                    <label class="custom-file-label" for="category-image">Choose file</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="button" class="btn btn-danger btn-md" id="removeMoreBtn" onclick="removeImgRow('${counter}')" value="-"/>
                                        </td>
                                    </tr>`);

        }

        function removeImgRow(counter) {
            $('#row_' + counter).remove();
        }

        function PreviewImage(counter) {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById('gallery_' + counter).files[0]);

            oFReader.onload = function(oFREvent) {
                document.getElementById('img_' + counter).src = oFREvent.target.result;
            };
        }

        $(document).ready(function() {
            $('select[name=product_type]').on('change', function() {

                if ($(this).val() == "Downloadable") {
                    $('#product_file_area').css('display', 'block');
                } else {
                    $('#product_file_area').css('display', 'none');

                }
            });

        });
    </script>
@endsection
