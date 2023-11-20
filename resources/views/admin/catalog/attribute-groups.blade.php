@extends('admin.layouts.app')
@section('title', 'Attribute Groups')
@section('page_css')
    <!-- Datatables -->
    <link href="{{ asset('admin/datatables/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <style>
        .addBtn{
            float: right;
            /*margin-top: 10px;*/
        }
        td{
            text-align: center;
        }
    </style>

@endsection
@section('section')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Attributes</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Attributes</li>
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

                                <a class="btn btn-primary pull-right addBtn" href="#">Add Attribute Group</a>
                            </div>
                            <div class="col-md-12">

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr style="text-align: center">
                                        <th>S.No</th>
                                        <th>Group Name</th>
                                        <th>Status</th>

                                        <th>Action</th>
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
        <div id="confirmModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header"  style="background-color: #343a40;
            color: #fff;">
                        <h2 class="modal-title">Confirmation</h2>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 align="center" style="margin: 0;">Are you sure you want to delete this ?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="ok_delete" name="ok_delete" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"  style="background-color: #343a40;
            color: #fff;">
                    <h2 class="modal-title">Attribute Group</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form class="attribute-group-form" method="post"
                      action="{{route('catalog.addAttributeGroup')}}"
                      enctype="multipart/form-data">
                <div class="modal-body">

                        @csrf
                        <div class="card-body">
                            @if(Session::has('msg'))
                                <div class="alert alert-success">{{Session::get('msg')}}</div>
                            @endif
{{--                            <label for="name" class="ml-2"></label>--}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name[]" id="name" value="" placeholder="Attribute Group Name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-right">

                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary btn-md">Submit</button></div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('admin/datatables/datatables.net/js/jquery.dataTables.min.js')}}"></script>


    <script>
        $(document).ready(function () {
            var DataTable = $("#example1").DataTable({
                dom: "Blfrtip",
                buttons: [{
                    extend: "copy",
                    className: "btn-sm"
                }, {
                    extend: "csv",
                    className: "btn-sm"
                }, {
                    extend: "excel",
                    className: "btn-sm"
                }, {
                    extend: "pdfHtml5",
                    className: "btn-sm"
                }, {
                    extend: "print",
                    className: "btn-sm"
                }],
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: {
                    url: `{{route('catalog.attributeGroups')}}`,
                },
                columns: [

                    {data: 'id', name: 'id'},

                    {data: 'attribute_group', name: 'attribute_group'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false}
                ]

            });
            var delete_id;
            $(document,this).on('click','.delete',function(){
                delete_id = $(this).attr('id');
                $('#confirmModal').modal('show');
            });
            $(document).on('click','#ok_delete',function(){
                $.ajax({
                    url:"{{url('admin/catalog/destroy-attribute-group')}}/"+delete_id,
                    type:'post',
                    data:{
                        id:delete_id,
                        "_method": 'DELETE',
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function(){
                        $('#ok_delete').text('Deleting...');
                        $('#ok_delete').attr("disabled",true);
                    },
                    success: function (data) {
                        DataTable.ajax.reload();
                        $('#ok_delete').text('Delete');
                        $('#ok_delete').attr("disabled",false);
                        $('#confirmModal').modal('hide');
                        //   js_success(data);
                        if(data==0) {
                            toastr.error('Exception Here ! Delete Firstly Child Attribute');
                        }else{
                            toastr.success('Record Delete Successfully');
                        }



                    }
                })
            });
        });

        $(document,this).on('click','.addBtn',function(){
            $('#addModal').modal('show');
        });
    </script>


@endsection
