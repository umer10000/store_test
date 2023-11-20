@extends('admin.layouts.app')
@section('title', 'Create Blog')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Search Banner</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ url('admin/dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Search Banner</li>
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
                                <h3 class="card-title">Search Banner</h3>
                            </div><br>
                            <!-- <div class="col-md-12 text-right">
                                <button class="btn btn-sm btn-success" id="add_more">Add More</button>
                            </div> -->
                            <form class="category-form" method="post"
                                action="{{ route('search-banner-store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (Session::has('msg'))
                                        <div class="alert alert-success">{{ Session::get('msg') }}</div>
                                    @endif
                                    <input type="hidden" name="id" id="id" value="{{ $banner->id??'' }}">
                                    <img src="{{ url($banner->banner_img??'') }}" id="blah" alt="banner image" width="400" height="200">
                                    <div class="form-group">
                                        <label for="name"> Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            name="image" id="imgInp" value="{{ old('image') }}" placeholder="image"
                                            required>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="add_more"></div>
                                    <!-- /.card-body -->
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                        <!-- <a href="{{ route('cms-first-section') }}"
                                            class="btn btn-warning btn-md">Cancel</a> -->
                                    </div>

                                </div>
                            </form>

                            <!-- /.card -->
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('admin/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/dropzone/dist/dropzone.js') }}"></script>
    <script>
        imgInp.onchange = evt => {
            const [file] = imgInp.files
            if (file) {
                blah.src = URL.createObjectURL(file)
            }
        }


        // window.onload = function() {
        //     CKEDITOR.replace('description', {
        //         {{-- filebrowserUploadUrl: '{{ route('project.document-image-upload',['_token' => csrf_token() ]) }}', --}}
        //         {{-- filebrowserUploadMethod: 'form' --}}
        //     });
        // };
        let counter = 0;
        $("#add_more").on('click', function() {
            counter++;
            $('.add_more').append(`
            <div class="row_${counter}">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-danger btn-rounded" onclick="removeRow(${counter})">Remove</button>    
            </div> 
            <div class="form-group">
                <label for="name"> Images</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror"
                    name="image[]" id="image" value="{{ old('image') }}" placeholder="image"
                    required>
                @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            </div>`);
        });

        function removeRow(id) {
            $(`.row_${id}`).remove();
        }
    </script>
@endsection
