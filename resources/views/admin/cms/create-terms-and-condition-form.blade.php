@extends('admin.layouts.app')
@section('title', 'Create Blog')
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
            <div class="row">
                <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                    <h3 class="card-title">
                        Summernote
                    </h3>
                    </div>
                    <!-- /.card-header -->
                    <form class="category-form" method="post" action="{{ route('terms-and-conditions-store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="cms_page_id" value="{{ $cmsSection->id }}">
                    <div class="card-body">
                        <textarea id="summernote" name="description" >                
                           Place &lt;em&gt;some&lt;/em&gt; &lt;u&gt;text&lt;/u&gt; &lt;strong&gt;here&lt;/strong&gt;
                        </textarea>
                    </div>
                   

                    <!-- /.card-body -->
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                        <a href="{{ route('cms-first-section') }}"
                                            class="btn btn-warning btn-md">Cancel</a>
                                    </div>
                    </form>
                    <!-- <div class="card-footer">
                    Visit <a href="https://github.com/summernote/summernote/">Summernote</a> documentation for more examples and information about the plugin.
                    </div> -->
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
    $(function () {
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
