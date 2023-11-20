@extends('admin.layouts.app')
@section('title', 'Edit First Section')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>CMS First Section</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ url('admin/dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">First Section Component</li>
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
                                <h3 class="card-title">First Section Component</h3>
                            </div>
                            <form class="category-form" method="post"
                                action="{{ route('first-section-component-update', ['id' => $id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (Session::has('msg'))
                                        <div class="alert alert-success">{{ Session::get('msg') }}</div>
                                    @endif
                                    @php $counter = 0; @endphp
                                    @foreach ($cmsSection as $component)
                                        @php $counter++; @endphp

                                        <div class="row_{{ $counter }}">
                                            @if (!$loop->first)
                                                {{-- <button type="button" class="btn btn-sm btn-danger btn-rounded"
                                                    style="float: right" onclick="removeRow({{ $counter }})">Remove
                                                </button> --}}
                                            @endif
                                            <div class="form-group">
                                                <label for="name"> Title</label>
                                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                    name="title[]" id="title" value="{{ $component->title }}"
                                                    placeholder="Title" required>
                                                <input type="hidden" name="cms_components_id[]" id="cms_components_id"
                                                    value="{{ $component->id }}">
                                                @error('title')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Description</label>
                                                <textarea class="form-control" name="description[]" id=""
                                                    placeholder="Description"
                                                    required>{{ $component->description }}</textarea>
                                            </div>
                                            <hr>
                                        </div>
                                    @endforeach
                                    <div class="add_more"></div>
                                    <!-- /.card-body -->
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                        <a href="{{ route('cms-first-section') }}"
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
                <label for="name"> Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror"
                    name="title[]" id="title" value="{{ old('title') }}" placeholder="Title"
                    required>
                @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="category">Description</label>
                <textarea class="form-control" name="description[]" id=""
                    placeholder="Description" required>{{ old('description') }}</textarea>
            </div></div>`);
        });

        function removeRow(id) {
            $(`.row_${id}`).remove();
        }
    </script>
@endsection
