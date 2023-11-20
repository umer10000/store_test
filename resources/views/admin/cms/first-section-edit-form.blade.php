@extends('admin.layouts.app')
@section('title', 'Edit Section')
@section('section')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>CMS @if ($cmsSection->section_name == 'second')
                                Second
                            @elseif($cmsSection->section_name == "first")
                                First
                            @elseif($cmsSection->section_name == "third")
                                Third
                            @endif Section</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ url('admin/dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Edit @if ($cmsSection->section_name == 'second')
                                    Second
                                @elseif($cmsSection->section_name == "first")
                                    First
                                @elseif($cmsSection->section_name == "third")
                                    Third
                                @endif Section</li>
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
                                <h3 class="card-title">Edit
                                    @if ($cmsSection->section_name == 'second')
                                        Second
                                    @elseif($cmsSection->section_name == "first")
                                        First
                                    @elseif($cmsSection->section_name == "third")
                                        Third
                                    @endif
                                    Section
                                </h3>
                            </div>
                            <form class="category-form" method="post"
                                action="{{ route('first-section-update', ['id' => $cmsSection->id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if (Session::has('msg'))
                                        <div class="alert alert-success">{{ Session::get('msg') }}</div>
                                    @endif
                                    <div class="form-group">
                                        <label for="name"> Heading</label>
                                        <input type="text" class="form-control @error('heading') is-invalid @enderror"
                                            name="heading" id="heading" placeholder="Heading" required
                                            value="{{ $cmsSection->heading }}">
                                        @error('heading')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    @if ($cmsSection->section_name == 'second')
                                        <div class="form-group">
                                            <label for="name">Component</label>
                                            <input type="text" class="form-control @error('heading') is-invalid @enderror"
                                                name="title[]" id="title[]" placeholder="Content" required
                                                value="{{ $cmsSection->components[0]->title ?? '' }}">
                                            <input type="hidden" name="component_id[]"
                                                value="{{ $cmsSection->components[0]->id ?? '' }}">
                                            @error('components')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Component</label>
                                            <input type="text" class="form-control @error('heading') is-invalid @enderror"
                                                name="title[]" id="title[]" placeholder="Content" required
                                                value="{{ $cmsSection->components[1]->title ?? '' }}">
                                            <input type="hidden" name="component_id[]"
                                                value="{{ $cmsSection->components[1]->id ?? '' }}">
                                            @error('components')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @endif
                                    <!-- /.card-body -->
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                        @if ($cmsSection->section_name == 'second')
                                            <a href="{{ route('cms-second-section') }}"
                                                class="btn btn-warning btn-md">Cancel</a>
                                        @elseif($cmsSection->section_name == "first")
                                            <a href="{{ route('cms-first-section') }}"
                                                class="btn btn-warning btn-md">Cancel</a>
                                        @else
                                            <a href="{{ route('cms-third-section') }}"
                                                class="btn btn-warning btn-md">Cancel</a>
                                        @endif
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

@endsection
