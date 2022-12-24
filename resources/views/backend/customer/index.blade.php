@extends('backend.layouts.master')
@section('title', 'E-SHOP || Customer Page')
@section('main-content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('backend.layouts.notification')
            </div>
        </div>
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">Customer List</h6>
            <a href="{{ route('customer.create') }}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip"
                data-placement="bottom" title="Add User"><i class="fas fa-plus"></i> Add Customer</a>

                <button class="btn btn-danger btn-sm float-right delete_selected" data-toggle="tooltip" data-placement="bottom" title="Delete All Selected" style="margin-right: 10px;"><i class="fas fa-trash-alt"></i>Delete All</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="customer-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" for='selectAll'/></th>
                            <th>User Type</th>
                            <th>Full Name</th>
                            <th>Firm Name</th>
                            <th>Email</th>
                            <th>Documents</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S.N.</th>
                            <th>User Type</th>
                            <th>Full Name</th>
                            <th>Firm Name</th>
                            <th>Email</th>
                            <th>Documents</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="accountDeclined" tabindex="-1" role="dialog">
        <div class="modal-dialog cart-history-model" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Declined</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <form method="POST" action="{{ route('customer.declined') }}">
                                    @csrf
                                    <input type="hidden" name="customer_id" />
                                    <div class="form-group">
                                        <label for="type" class="col-form-label">Enter Resion <span
                                                class="text-danger">*</span></label>
                                        <textarea name="resion" class="form-control" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->
@endsection

@push('styles')
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" /> --}}
    <style>
        /* div.dataTables_wrapper div.dataTables_paginate {
            display: none;
        } */

    </style>
@endpush

@push('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('backend/js/demo/datatables-demo.js') }}"></script>
    <script>
        // $('#customer-dataTable').DataTable({
        //     "columnDefs": [{
        //         "orderable": false,
        //         "targets": [3, 4]
        //     }]
        // });
        var table = $('#customer-dataTable').DataTable({
            "ajax": {
                url: "{{ route('customer.index') }}",
            },
            "processing": true,
            "serverSide": true,
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            "columns": [
                {data: 'select_orders', name: 'id', orderable: false, searchable: false},
                //{data: 'DT_RowIndex', name: 'id', orderable: false, searchable: false},
                {data: 'role_name', name: 'role_name'},
                {data: 'full_name', name: 'full_name'},
                {data: 'firm_name', name:'firm_name', orderable: false, searchable: false},
                {data: 'email', name:'email', orderable: false, searchable: false},
                {data: 'documentData', name:'documentData', orderable: false, searchable: false},
                {data: 'info_status', name:'info_status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
        });
        $(document).ready(function() {

            $('.btnAccountDeclined').on('click', function() {
                $("input[name='customer_id']").val($(this).data('id'));
            });
            $(document).on('click', '.customerIsActive', function(e) {
                $status = ($(this).is(":checked")) ? 1 : 0;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                $.ajax({
                    url: "{{ route('customer.change.status') }}",
                    method: 'post',
                    data: {
                        customer_id: $(this).data('id'),
                        status: $status
                    },
                    success: function(result) {
                        // console.log(result);
                    }
                });
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.dltBtn').click(function(e) {
                var form = $(this).closest('form');
                var dataID = $(this).data('id');
                // alert(dataID);
                e.preventDefault();
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this data!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            form.submit();
                        } else {
                            swal("Your data is safe!");
                        }
                    });
            });

            $("#selectAll").click(function() {
                $(".rowID").prop("checked", $(this).prop("checked"));
            });

            $(document).on('change', '.rowID', function(e) {
                if (!$(this).prop("checked")) {
                    $("#selectAll").prop("checked", false);
                } else {
                    var all = $('.rowID');
                    if (all.length === all.filter(':checked').length) {
                        $("#selectAll").prop("checked", true);
                    } else {
                        $("#selectAll").prop("checked", false);
                    }
                }
            });

            $(document).on('click', '.delete_selected', function(e) {
                var yourArray = [];
                $(".rowID:checked").each(function(){
                    yourArray.push($(this).val());
                });
                if(yourArray.length > 0) {

                    e.preventDefault();
                    swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this data!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "{{ route('customer.delete.multiple') }}",
                                method: 'post',
                                data: {
                                    ids: yourArray,
                                },
                                success: function(result) {
                                    table.draw();
                                }
                            });
                        } else {
                            swal("Your data is safe!");
                        }
                    });
                } else {
                    swal("Please check any checkbox");
                }
            });
        });
    </script>
@endpush
