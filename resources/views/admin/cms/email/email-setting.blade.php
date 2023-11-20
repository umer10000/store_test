@extends('admin.layouts.app')
@section('title', 'Email Template Setting')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Email Template Setting</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ url('admin/dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Email Template</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content-header -->
        <section class="content">
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Email Template Setting</h3>
                        </div>
                        <!-- /.card-header -->
                        <form class="category-form" method="post" action="{{ route('post-email-setting') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $emailSetting->id }}">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Logo</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="logo"
                                                        id="category-banner"
                                                        onchange="PreviewImage('category-banner','banner')">
                                                    <label class="custom-file-label" for="category-image">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <img id="banner"
                                            src="{{ asset(isset($emailSetting->logo) ? 'uploads/email/' . $emailSetting->logo : 'admin/images/placeholder.png') }}"
                                            alt="" style="height: 200px;width: 200px;">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Image</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="email_image"
                                                        id="image-banner"
                                                        onchange="PreviewImage('image-banner','email_image')">
                                                    <label class="custom-file-label" for="category-image">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <img id="email_image"
                                            src="{{ asset(isset($emailSetting->image) ? 'uploads/email/' . $emailSetting->image : 'admin/images/placeholder.png') }}"
                                            alt="" style="height: 200px;width: 200px;">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Content</label>
                                        <textarea id="" class="form-control" name="description"
                                            required>{{ $emailSetting->content }}</textarea>
                                    </div>
                                </div>
                            </div>


                            <!-- /.card-body -->
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                {{-- <a href="{{ route('cms-first-section') }}" class="btn btn-warning btn-md">Cancel</a> --}}
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.col-->
            </div>

        </section>


    </div>
@endsection
@section('script')
    <script src="{{ asset('admin/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/dropzone/dist/dropzone.js') }}"></script>
    <script>
        function PreviewImage(inputId, id) {

            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById(inputId).files[0]);

            oFReader.onload = function(oFREvent) {
                document.getElementById(id).src = oFREvent.target.result;
            };
        }

        // $(function() {
        //     // Summernote
        //     $('#summernote').summernote()

        //     // CodeMirror
        //     CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
        //         mode: "htmlmixed",
        //         theme: "monokai"
        //     });
        // });
    </script>

@endsection
