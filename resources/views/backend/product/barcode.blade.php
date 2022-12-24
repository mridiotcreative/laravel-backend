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
            <h6 class="m-0 font-weight-bold text-primary float-left">{{ isset($product->title) ? $product->title : old('title') }}</h6>

            <button class="btn btn-primary btn-sm float-right print_barcode" data-toggle="tooltip" data-placement="bottom" title="Print Barcode" style="margin-left: 10px;">Print Barcode</button>

            <button data-toggle="modal" data-target="#exampleModal" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add User" style="margin-left: 10px;"><i class="fas fa-plus"></i> Add Barcoode</button>

            <select id="batch_number_id" class="float-right col-md-2 form-control selectpicker" data-live-search="true">
                <option value="">--Select batch number --</option>
                @foreach($incentive_barcode_data as $barcodeBatchnumber)
                    <option value="{{ $barcodeBatchnumber->batch_number }}">{{ $barcodeBatchnumber->batch_number }} ({{ $barcodeBatchnumber->count_of_barcode }})</option>
                @endforeach
            </select>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="barcode-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" for='selectAll'/></th>
                            <th>Number</th>
                            <th>Image</th>
                            <th>Point</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S.N.</th>
                            <th>Number</th>
                            <th>Image</th>
                            <th>Point</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Barcode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="addBarcode">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ request('id') }}">
                        <div class="form-group">
                            <label for="inputTitle" class="col-form-label">Point <span class="text-danger">*</span></label>
                            <input id="inputTitle" type="number" min="0" oninput="this.value = Math.abs(this.value)" name="barcode_point" placeholder="Enter Point" value="" class="form-control" require>
                        </div>
                        <div class="form-group">
                            <label for="barcodeinputTitle" class="col-form-label">Barcode Count <span class="text-danger">*</span></label>
                            <input id="barcodeinputTitle" type="number" min="0" oninput="this.value = Math.abs(this.value)" name="barcode_count" placeholder="Enter Barcode Count" value="" class="form-control" require>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-save">Save changes</button>
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

        .zoom {
            transition: transform .2s;
            /* Animation */
        }

        .zoom:hover {
            transform: scale(5);
        }

        .error {
            color: #d12008 !important;
            font-size: 1rem !important;
            position: relative !important;
            line-height: 1 !important;
            width: 29.2rem !important;
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
    <script src="{{ asset('frontend/js/jquery.validate.min.js') }}"></script>
    <script>

        var table = $('#barcode-dataTable').DataTable({
            "ajax": {
                url: "{{ route('barcode.list', request('id')) }}",
                data: function ( d ) {
                    d.batch_number_id = $('#batch_number_id').val();
                },
            },
            "processing": true,
            "serverSide": true,
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            "columns": [
                {data: 'select_orders', name: 'id', orderable: false, searchable: false},
                {data: 'barcode_number', name: 'barcode_number', orderable: false, searchable: false},
                {data: 'barcode_photo', name:'barcode_photo', orderable: false, searchable: false},
                {data: 'points', name: 'points', orderable: false, searchable: false},
                {data: 'info_status', name:'info_status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });

        function printData(checkboxArray) {
            var divToPrint= '';
            let x = Math.floor((Math.random() * 10) + 1);
            for (let i = 0; i < checkboxArray.length; i++){
                element = document.getElementById('img_'+checkboxArray[i]);
                var newWin=window.open('','newWin'+x+'');
                newWin.document.write('<head><title>Barcode Tab '+x+'</title></head>'+element.outerHTML+'<br><br>');
            }
            setTimeout(function () {
                newWin.print();
            }, 1000)
        }

    </script>
    <script>
        $(document).ready(function() {

            $("#selectAll").click(function() {
                $(".barCodeID").prop("checked", $(this).prop("checked"));
            });
            
            $("#batch_number_id").change(function() {
                table.draw();
            });

            $(document).on('change', '.barCodeID', function(e) {
                if (!$(this).prop("checked")) {
                    $("#selectAll").prop("checked", false);
                } else {
                    var all = $('.barCodeID');
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

            $(document).on('click', '.dltBtn', function(e) {
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
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }
                        });
                        $.ajax({
                            url: "{{ route('barcode.destroy') }}",
                            method: 'post',
                            data: {
                                id: $(this).data('id')
                            },
                            success: function(result) {
                                table.draw();
                            }
                        });
                    } else {
                        swal("Your data is safe!");
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
                    url: "{{ route('barcode.change.status') }}",
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

            $(document).on('click', '.print_barcode', function(e) {
                var yourArray = [];
                $(".barCodeID:checked").each(function(){
                    yourArray.push($(this).val());
                });
                (yourArray.length > 0) ? printData(yourArray) : swal("Please check any checkbox");
            });

            $('#addBarcode').validate({
                rules: {
                    barcode_point: {
                        required: true
                    },
                    barcode_count: {
                        required: true
                    },
                },
                messages: {
                    barcode_point: {
                        required: "Field is required."
                    },
                    barcode_count: {
                        required: "Field is required."
                    },
                }
            });

            $('#btn-save').click(function() {
                if ($('#addBarcode').valid()) {
                    $.ajax({
                        url: "{{ route('generate.barcode') }}",
                        method: 'post',
                        data: $('#addBarcode').serialize(),
                        dataType: 'json', 
                        success: function(response) {
                            if (response.status == 200) {
                                $('#exampleModal').modal('hide');
                                table.draw();
                                $("#addBarcode").validate().resetForm();
                                $('#addBarcode')[0].reset();
                            }
                        },
                        error: function(request, status, error) {
                            var obj = jQuery.parseJSON(request.responseText);
                            var errorHtml = "";
                            var i = 0;
                            $.each(obj.errors, function( index, value ) {
                                i++;
                                errorHtml += i+' : '+value[0]+"\r\n";
                            });
                            swal(errorHtml);
                        }
                    });
                }
            });

        })
    </script>
@endpush
