@extends('admin.layouts.app')
@section('title','Create Blog')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Blog Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Blog Form</li>
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
                                <h3 class="card-title">Blog</h3>
                            </div>
                            <form class="category-form" method="post" action="{{url('admin/blog/'.$blog->id)}}" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif
                                    <div class="form-group">
                                        <label for="name">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" value="{{$blog->title}}" placeholder="Blog Title" required>
                                        @error('title')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Description</label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Description" >{{$blog->description}}</textarea>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Blog Image</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="image" onchange="PreviewImage()" id="blog-image" accept="image/*">
                                                            <label class="custom-file-label" for="blog-image">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <img src="{{asset(isset($blog->image) ?'uploads/blog/'.$blog->image : 'admin/images/placeholder.png')}}" alt="" id="img" style="height: 200px;width: 200px;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                        <a href="{{route('blog.index')}}" class="btn btn-warning btn-md">Cancel</a>
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
        window.onload = function () {
            CKEDITOR.replace('description', {
                {{--filebrowserUploadUrl: '{{ route('project.document-image-upload',['_token' => csrf_token() ]) }}',--}}
                {{--filebrowserUploadMethod: 'form'--}}
            });
        };

        function PreviewImage() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById('blog-image').files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById('img').src = oFREvent.target.result;
            };
        }
    </script>
@endsection
