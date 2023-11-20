@extends('admin.layouts.app')
@section('title', 'Products')
@section('page_css')
    <!-- Datatables -->
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    {{-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> --}}
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

    </style>
    <style>
        .addBtn {
            float: right;
            /*margin-top: 10px;*/
        }

        td {
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
                        <h1>Special Deals Products</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Special Deals Products</li>
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
                                <a class="btn btn-primary addBtn" href="{{ route('deals.create') }}">Add Special Deal</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            {{-- <th>ID</th> --}}
                                            <th>Product</th>
                                            <th>Seller</th>
                                            <th>Amount</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
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
                    <div class="modal-header" style="background-color: #343a40; color: #fff;">
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
@endsection
@section('script')


    <script src="{{ asset('admin/datatables/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
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
                    url: `{{ route('deals.index') }}`,
                },
                columns: [

                    // {
                    //     data: 'id',
                    //     name: 'id'
                    // },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'seller',
                        name: 'seller'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'is_approve',
                        name: 'is_approve'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ]

            });

            var delete_id;
            $(document, this).on('click', '.delete', function() {
                delete_id = $(this).attr('id');
                $('#confirmModal').modal('show');
            });

            $(document).on('click', '#ok_delete', function() {
                $.ajax({
                    type: "delete",
                    url: `{{ url('admin/' . request()->segment(2) . '/') }}/destroy/${delete_id}`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#ok_delete').text('Deleting...');
                        $('#ok_delete').attr("disabled", true);
                    },
                    success: function(data) {
                        DataTable.ajax.reload();
                        $('#ok_delete').text('Delete');
                        $('#ok_delete').attr("disabled", false);
                        $('#confirmModal').modal('hide');
                        //   js_success(data);
                        if (data == 0) {
                            toastr.error('Exception Here ! Delete Firstly Child Category');
                        } else {
                            toastr.success('Record Delete Successfully');
                        }

                    }
                })
            });


            $(document).on('click', '#status-switch', function() {
                let id = $(this).data('id');
                let val = $(this).data('val');
                $.ajax({
                    type: "get",
                    url: `{{ url('admin/' . request()->segment(2) . '/changeDealsProductStatus') }}/${id}`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        val: val
                    },
                    success: function(data) {
                        DataTable.ajax.reload();

                        if (data == 0) {
                            toastr.error('Exception Here !');
                        } else {
                            toastr.success('Record Status Updated Successfully');
                        }



                    }
                })
            });
        })
    </script>


@endsection
