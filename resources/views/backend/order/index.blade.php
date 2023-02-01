@extends('backend.layouts.master')

@section('main-content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('backend.layouts.notification')
            </div>
        </div>
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">Order Lists</h6>
            <button class="btn btn-danger btn-sm float-right delete_selected" data-toggle="tooltip" data-placement="bottom" title="Delete All Selected"><i class="fas fa-trash-alt"></i>Delete All</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="order-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" for='selectAll'/></th>
                            <th>Order No.</th>
                            <th>Name</th>
                            {{-- <th>Firm Name</th> --}}
                            <th>Email</th>
                            <th>Sub Total Amount</th>
                            {{-- <th>Points</th> --}}
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S.N.</th>
                            <th>Order No.</th>
                            <th>Name</th>
                            {{-- <th>Firm Name</th> --}}
                            <th>Email</th>
                            <th>Sub Total Amount</th>
                            {{-- <th>Points</th> --}}
                            <th>Total Amount</th>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="updateOrderStatus">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="orderID" name="orderID">
                        <div class="form-group">
                            <label for="status">Status :</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="process">process</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancel">Cancel</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="updateOrderStatus btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
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
    <script src="{{ asset('frontend/js/jquery.validate.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('backend/js/demo/datatables-demo.js') }}"></script>
    <script>
        // $('#order-dataTable').DataTable({
        //     "columnDefs": [{
        //         "orderable": false,
        //         "targets": [8]
        //     }]
        // });

        // Sweet alert

        function deleteData(id) {

        }
    </script>
    <script>
        $(document).ready(function() {

            var table = $('#order-dataTable').DataTable({
                "ajax": {
                    url: "{{ route('order.index') }}",
                },
                "processing": true,
                "serverSide": true,
                "bPaginate": true,
                "sPaginationType": "full_numbers",
                "columns": [
                    {data: 'select_orders', name: 'id', orderable: false, searchable: false},
                    //{data: 'DT_RowIndex', name: 'id', orderable: false, searchable: false},
                    {data: 'order_number', name: 'order_number'},
                    {data: 'full_name', name: 'full_name'},
                    // {data: 'firm_name', name:'firm_name', orderable: false, searchable: false},
                    {data: 'email', name:'email', orderable: false, searchable: false},
                    {data: 'sub_total_amount', name:'sub_total_amount', orderable: false, searchable: false},
                    // {data: 'points', name:'points', orderable: false, searchable: false},
                    {data: 'total_amount', name:'total_amount', orderable: false, searchable: false},
                    {data: 'info_status', name:'info_status', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
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
            })

            $(document).on('click', '.change_status',function(e) {
                e.preventDefault();
                var dataID = $(this).data('id');
                var status = $(this).data('status');
                $("#status").val(status);
                $('#orderID').val(dataID);
            });

            $('.updateOrderStatus').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('order.update-status') }}",
                    method: 'post',
                    data: $('#updateOrderStatus').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        $('#exampleModal').modal('hide')
                        table.draw();
                    },
                    error: function(request, status, error) {
                        swal(request.responseText.errors.status);
                    }
                });
            });

            $(document).on('change', '.changeStatus', function(e) {
                var status = ($(this).is(":checked")) ? 1 : 0;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                $.ajax({
                    url: "{{ route('product.change.status') }}",
                    method: 'post',
                    data: {
                        id: $(this).data('id'),
                        status: status
                    },
                    success: function(result) {
                        table.draw();
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
                                url: "{{ route('order.delete.multiple') }}",
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
        })
    </script>
@endpush
