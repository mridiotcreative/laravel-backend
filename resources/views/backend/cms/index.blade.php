@extends('backend.layouts.master')
@section('title', 'E-SHOP || Banner Page')
@section('main-content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('backend.layouts.notification')
            </div>
        </div>
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">CMS List</h6>
            <a href="{{ route('cms.create') }}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip"
                data-placement="bottom" title="Add User"><i class="fas fa-plus"></i> Add CMS</a>

            <button class="btn btn-danger btn-sm float-right delete_selected" data-toggle="tooltip" data-placement="bottom" title="Delete All Selected" style="margin-right: 10px;"><i class="fas fa-trash-alt"></i>Delete All</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="cms-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" for='selectAll'/></th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S.N.</th>
                            <th>Title</th>
                            <th>Slug</th>
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
@endsection

@push('styles')
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <style>
        /* div.dataTables_wrapper div.dataTables_paginate {
            display: none;
        } */

        .zoom {
            transition: transform .2s;
            /* Animation */
        }

        .zoom:hover {
            transform: scale(3.2);
        }

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

        var table = $('#cms-dataTable').DataTable({
            "ajax": {
                url: "{{ route('cms.index') }}",
            },
            "processing": true,
            "serverSide": true,
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            "columns": [
                {data: 'select_orders', name: 'id', orderable: false, searchable: false},
                //{data: 'DT_RowIndex', name: 'id', orderable: false, searchable: false},
                {data: 'title', name: 'title'},
                {data: 'slug', name: 'slug'},
                {data: 'info_status', name:'info_status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
        });

        // Sweet alert

        function deleteData(id) {

        }
    </script>
    <script>
        $(document).ready(function() {
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

            $(document).on('change', '.changeStatus', function(e) {
                var status = ($(this).is(":checked")) ? 1 : 0;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                $.ajax({
                    url: "{{ route('cms.change.status') }}",
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
                                url: "{{ route('cms.delete.multiple') }}",
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
