@extends('admin.layouts.app')
@section('title', (isset($content->name) ? $content->name : '') . ' Category')
@section('page_css')
    <!-- Datatables -->
    <link href="{{ asset('admin/datatables/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/datatables/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/datatables/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('admin/datatables/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('admin/datatables/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}"
        rel="stylesheet">
    <style>
        th {
            background-color: #f7f7f7;
        }

    </style>
@endsection
@section('section')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-sm-6 offset-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Category</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Category Detail</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width:25%">ID</th>
                                            <td>{{ $content->id ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>{{ $content->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Parent Category</th>
                                            <td>{{ $content->sub_category->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Slug</th>
                                            <td>{{ $content->category_slug ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td colspan="5">{{ $content->description ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Meta Tag Title</th>
                                            <td colspan="5">{{ $content->meta_tag_title ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Meta Tag Keywords</th>
                                            <td colspan="5">{{ $content->meta_tag_keywords ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Thumbnail</th>
                                            <td colspan="4">
                                                @if (!empty($content->category_image) && file_exists('uploads/category/' . $content->category_image))
                                                    <img src="{{ asset('uploads/category/' . $content->category_image) }}"
                                                        height="100" width="100">
                                                @else
                                                    Image is not available
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Banner</th>
                                            <td colspan="4">
                                                @if (!empty($content->category_banner) && file_exists('uploads/category/' . $content->category_banner))
                                                    <img src="{{ asset('uploads/category/' . $content->category_banner) }}"
                                                        height="100" width="100">
                                                @else
                                                    Image is not available
                                                @endif
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>

    </div>
@endsection
