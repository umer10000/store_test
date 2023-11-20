@extends('admin.layouts.app')
@section('title', 'Create Blog')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>CMS Third Section</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ url('admin/dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Third Section Component</li>
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
                                <h3 class="card-title">Third Section Component</h3>
                            </div> <br>
                            <div class="col-md-12 text-right">
                                <button class="btn btn-sm btn-success" id="add_more">Add More</button>
                            </div>
                            <form class="category-form" method="post"
                                action="{{ route('third-section-component-update', ['id' => $id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (Session::has('msg'))
                                        <div class="alert alert-success">{{ Session::get('msg') }}</div>
                                    @endif
                                    <div class="form-group">
                                        <label for="category">Description</label>
                                        <textarea id="summernote"
                                            name="description"> {{ $description->description }} </textarea>
                                        <!-- <textarea class="form-control" name="description" id="" placeholder="Description"
                                                                            required>{{ $description->description }}</textarea> -->
                                        <input type="hidden" id="cms_components_id" name="cms_components_id"
                                            value="{{ $description->id }}">
                                    </div> <br>
                                    @php $counter = 0; @endphp
                                    @foreach ($cmsSection as $cms_image)
                                        @php $counter++; @endphp
                                        <div class="row_{{ $counter }}">
                                            <input type="hidden" name="cms_images_id[]" id="cms_images_id"
                                                value="{{ $counter }}">
                                            <button type="button" class="btn btn-sm btn-danger btn-rounded"
                                                style="float: right"
                                                onclick="removeRow({{ $counter }})">Remove</button>
                                            <div class="form-group">
                                                <label for="name"> Image</label>
                                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                                    name="image[]" id="image" value="{{ $cms_image->image }}"
                                                    placeholder="image">
                                                <input type="hidden" name="image_val[]" id="image_val"
                                                    value="{{ $cms_image->image }}">
                                                @error('image')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <img src="{{ url('uploads/cms/slider/' . $cms_image->image) }}" width="300px"
                                                height="150px">

                                            <hr>
                                        </div>
                                    @endforeach
                                    <div class="add_more"></div>
                                    <!-- /.card-body -->
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                        <a href="{{ route('cms-third-section') }}"
                                            class="btn btn-warning btn-md">Cancel</a>
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
        $(function() {
            // Summernote
            $('#summernote').summernote()

            // CodeMirror
            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                mode: "htmlmixed",
                theme: "monokai"
            });
        });
    </script>
    <script>
        // window.onload = function() {
        //     CKEDITOR.replace('description', {
        //         {{-- filebrowserUploadUrl: '{{ route('project.document-image-upload',['_token' => csrf_token() ]) }}', --}}
        //         {{-- filebrowserUploadMethod: 'form' --}}
        //     });
        // };
        let counter = {{ $counter }};
        $("#add_more").on('click', function() {
            counter++;
            $('.add_more').append(`
            <div class="row_${counter}">
            <input type="hidden" name="cms_images_id[]" id="cms_images_id" value="${counter}">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-danger btn-rounded" onclick="removeRow(${counter})">Remove</button>    
            </div> 
            <div class="form-group">
                <label for="name"> Image </label>
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
