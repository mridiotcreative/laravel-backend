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
            <h6 class="m-0 font-weight-bold text-primary float-left">Category Lists</h6>
            
            <!-- <button class="btn btn-primary btn-sm float-right import_csv" data-toggle="tooltip"
            data-placement="bottom" title="Upload" style="margin-left: 10px;"><i class="fa fa-upload"></i> Upload</button>

            <form action="javascript:void(0)" method="POST" enctype="multipart/form-data" id="categoryForm">
                @csrf
                <input type="file" class="upload_btn" style="display:none" name="import_csv">
            </form>

            <button data-href="{{ route('category.export.csv') }}" class="btn btn-primary btn-sm float-right export_csv" data-toggle="tooltip"
            data-placement="bottom" title="Download Template" style="margin-left: 10px;"><i class="fa fa-download"></i> Download Template</button> -->

            <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip"
                data-placement="bottom" title="Add Category"><i class="fas fa-plus"></i> Add Category</a>

            <button class="btn btn-danger btn-sm float-right delete_selected" data-toggle="tooltip"
                data-placement="bottom" title="Delete All Selected" style="margin-right: 10px;"><i class="fas fa-trash-alt"></i>Delete All</button>
            </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="category-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" for='selectAll'/></th>
                            <th>Title</th>
                            <th>Slug</th>
                            <!-- <th>Is Parent</th> -->
                            <th>Featured Category</th>
                            <th>Photo</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                    <tfoot>
                        <tr>
                            <th>S.N.</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <!-- <th>Is Parent</th> -->
                            <th>Featured Category</th>
                            <th>Photo</th>
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
        // $('#banner-dataTable').DataTable({
        //     "columnDefs": [{
        //         "orderable": false,
        //         "targets": [3, 4, 5]
        //     }]
        // });

        function deleteData(id) {

        }
    </script>
    <script>
        $(document).ready(function() {

            var table = $('#category-dataTable').DataTable({
                "ajax": {
                    url: "{{ route('category.index') }}",
                },
                "processing": true,
                "serverSide": true,
                "bPaginate": true,
                "sPaginationType": "full_numbers",
                "columns": [
                    {data: 'select_orders', name: 'id', orderable: false, searchable: false},
                    // {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'slug', name: 'slug'},
                    // {data: 'is_parent', name:'is_parent'},
                    {data: 'is_featured', name:'is_featured', orderable: false, searchable: false},
                    {data: 'image', name:'image', orderable: false, searchable: false},
                    {data: 'info_status', name:'info_status', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                // columnDefs: [
                //     { orderable: false, targets: [3, 4, 5] }
                // ]
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
            new
            $('.export_csv').click(function () {
                var url = $(this).data('href');
                window.location.href = url;
            })

            $('.import_csv').click(function () {
                $('.upload_btn').click();
            });
            
            $('.upload_btn').change(function () {
                var formData = new FormData($('#categoryForm')[0]);
                $.ajax({
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    method: 'POST',
                    url: "{{ route('category.import.csv') }}",
                    data: formData,
                    //cache:false,
                    contentType: false,
                    processData: false,
                    dataType: 'json', 
                    success: function(response) {

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
                    url: "{{ route('category.change.status') }}",
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
                                url: "{{ route('category.delete.multiple') }}",
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
