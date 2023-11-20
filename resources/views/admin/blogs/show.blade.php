@extends('admin.layouts.app')
@section('title', 'Blog Details')
@section('page_css')
    <style>
        th{
            background-color: #f7f7f7;
        }
    </style>
@endsection
@section('section')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Blog</li>
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
                                <h3 class="card-title">Blog Detail</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <td colspan="2">{{$blog->id ??''}}</td>
                                        <th>Title</th>
                                        <td>{{$blog->title ??''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td colspan="4" style=" word-break: break-all;">{!! $blog->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Image</th>
                                        <td colspan="4">
                                            @if(!empty($blog->image) && file_exists('uploads/blog/'.$blog->image))
                                                <img src="{{asset('uploads/blog/'.$blog->image)}}" height="100" width="100">
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
