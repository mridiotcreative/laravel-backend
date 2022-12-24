@extends('backend.layouts.master')
@section('title','E-SHOP || Comment Page')
@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Comment Lists</h6>
      <button class="btn btn-danger btn-sm float-right delete_selected" data-toggle="tooltip" data-placement="bottom" title="Delete All Selected" style="margin-right: 10px;"><i class="fas fa-trash-alt"></i>Delete All</button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="comment-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th><input type="checkbox" id="selectAll" for='selectAll'/></th>
              <th>Author</th>
              <th>Post Title</th>
              <th>Message</th>
              <th>Date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>S.N.</th>
              <th>Author</th>
              <th>Post Title</th>
              <th>Message</th>
              <th>Date</th>
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
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      /* div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      } */
  </style>
@endpush

@push('scripts')

  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
  <script>

      // $('#order-dataTable').DataTable( {
      //       "columnDefs":[
      //           {
      //               "orderable":false,
      //               "targets":[5,6]
      //           }
      //       ]
      //   } );

        // Sweet alert

        function deleteData(id){

        }
  </script>
  <script>
      $(document).ready(function(){
        var table = $('#comment-dataTable').DataTable({
            "ajax": {
                url: "{{ route('comment.index') }}",
            },
            "processing": true,
            "serverSide": true,
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            "columns": [
                {data: 'select_orders', name: 'id', orderable: false, searchable: false},
                //{data: 'DT_RowIndex', name: 'id', orderable: false, searchable: false},
                {data: 'user_info_name', name: 'user_info_name'},
                {data: 'post_title', name: 'post_title'},
                {data: 'comment', name: 'comment'},
                {data: 'comment_date', name: ''},
                {data: 'info_status', name:'info_status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
          $('.dltBtn').click(function(e){
            var form=$(this).closest('form');
              var dataID=$(this).data('id');
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
                                url: "{{ route('comment.delete.multiple') }}",
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
