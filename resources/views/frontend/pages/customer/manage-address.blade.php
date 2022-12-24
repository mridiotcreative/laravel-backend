@extends('frontend.layouts.master')
@section('title', __('page_title.manage_address'))
@section('main-content')
    <section class="product-area shop-sidebar shop-list shop my-account-main">
        <div class="account-breadcrum">
            <div class="col-md-12">
                <div class="container">
                    <section class="my-account-title">
                        <h3 class="title">My Account</h3>
                        <i class="fa fa-angle-right"></i>
                        <h5 class="title">Order History</h5>
                    </section>
                </div>

            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-12">
                    @include('frontend.layouts.sidebar')
                </div>
                <div class="col-lg-8 col-md-8 col-12">
                    <div class="my-account-main-rignt order-history">
                        <div class="order-history-main">
                            <h4 class="text-left title">Manage Address</h4>
                            <div class="order-history-dropdown">
                                <div class="add-address" data-toggle="modal" data-target="#modelAddress">
                                    <button class="view-detail-order">
                                        <span>
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 4.16602V15.8327" stroke="white" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M4.1665 10H15.8332" stroke="white" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                        Add Address</button>
                                </div>
                            </div>
                        </div>
                        <div class="order-status-main-section manage-address-main-wrapper">
                            <ul id="address-list">
                                @foreach ($addresses as $key => $address)
                                    <li>
                                        <div class="select-address-main">
                                            <div class="select-address-radio">
                                                <input type="radio" id="{{ $key }}" data-id="{{ $address->id }}"
                                                    class="primary_address" value="{{ $address->id }}"
                                                    name="primary_address"
                                                    {{ $address->is_primary == config('constants.IS_PRIMARY') ? 'checked' : '' }} />
                                                <label for="{{ $key }}">
                                                    <div class="summary-address">
                                                        <p>
                                                            {{ $address->building_name }},
                                                            {{ $address->street_name }},
                                                            {{ $address->city }} {{ $address->pincode }}.
                                                        </p>
                                                    </div>
                                                </label>
                                                <div class="address-delete">
                                                    <form method="POST"
                                                        action="{{ route('user.address.destroy', [$address->id]) }}">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="button" class="dltBtn"
                                                            data-id={{ $address->id }}>
                                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M2.25 4.5H3.75H15.75" stroke="black"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path
                                                                    d="M6 4.5V3C6 2.60218 6.15804 2.22064 6.43934 1.93934C6.72065 1.65804 7.10218 1.5 7.5 1.5H10.5C10.8978 1.5 11.2794 1.65804 11.5607 1.93934C11.842 2.22064 12 2.60218 12 3V4.5M14.25 4.5V15C14.25 15.3978 14.092 15.7794 13.8107 16.0607C13.5294 16.342 13.1478 16.5 12.75 16.5H5.25C4.85218 16.5 4.47064 16.342 4.18934 16.0607C3.90804 15.7794 3.75 15.3978 3.75 15V4.5H14.25Z"
                                                                    stroke="black" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M7.5 8.25V12.75" stroke="black"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M10.5 8.25V12.75" stroke="black"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="edit-address">
                                                    <button type="button" class="addressEdit" data-toggle="modal"
                                                        data-target="#modelAddress" data-id={{ $address->id }}>
                                                        <div class="edit-ic">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M7.3335 2.66602H2.66683C2.31321 2.66602 1.97407 2.80649 1.72402 3.05654C1.47397 3.30659 1.3335 3.64573 1.3335 3.99935V13.3327C1.3335 13.6863 1.47397 14.0254 1.72402 14.2755C1.97407 14.5255 2.31321 14.666 2.66683 14.666H12.0002C12.3538 14.666 12.6929 14.5255 12.943 14.2755C13.193 14.0254 13.3335 13.6863 13.3335 13.3327V8.66602"
                                                                    stroke="#F58220" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M12.3335 1.66617C12.5987 1.40095 12.9584 1.25195 13.3335 1.25195C13.7086 1.25195 14.0683 1.40095 14.3335 1.66617C14.5987 1.93138 14.7477 2.29109 14.7477 2.66617C14.7477 3.04124 14.5987 3.40095 14.3335 3.66617L8.00016 9.9995L5.3335 10.6662L6.00016 7.9995L12.3335 1.66617Z"
                                                                    stroke="#F58220" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </div>
                                                        <div class="edit-txt">
                                                            <p>Edit</p>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade manage-address-model" id="modelAddress" tabindex="-1" role="dialog">
        <div class="modal-dialog cart-history-model" role="document">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            class="ti-close" aria-hidden="true"></span></button>
                </div> --}}
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="manage-address-form-main">
                                    <div class="add-address-head">
                                        <h1 id="address-title">Manage Address</h1>
                                    </div>
                                    <form action="{{ route('user.address.storeOrUpdate') }}" method="post"
                                        id="address-form" autocomplete="off">
                                        @csrf
                                        <input type="hidden" name="address_id" />
                                        <div class="container manage-address-container">
                                            <label for="building_name">Building Name*</label>
                                            <input type="text" id="building_name" name="building_name">

                                            <label for="street_name">Street Name*</label>
                                            <input type="text" id="street_name" name="street_name">

                                            <label for="pincode">Pincode*</label>
                                            <input type="number" id="pincode" name="pincode">

                                            <label for="city">City*</label>
                                            <input type="text" id="city" name="city">
                                        </div>
                                        <div class="manage-address-btn-main">
                                            <button id="btn-save" type="button">
                                                Save
                                            </button>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('frontend/js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            function reloadAddressList() {
                $.ajax({
                    url: "{{ route('user.address.list') }}",
                    method: 'get',
                    success: function(result) {
                        if (result.address_list && result.address_list.length > 0) {
                            var isPrimary = "{{ config('constants.IS_PRIMARY') }}";
                            $('#address-list').empty();
                            result.address_list.forEach((item, key) => {
                                $('#address-list').append(`
                                     <li>
                                        <div class="select-address-main">
                                            <div class="select-address-radio">
                                                <input type="radio" id="address-${key}" data-id="${item.id}"
                                                    class="primary_address" value="${item.id}"
                                                    name="primary_address"
                                                    ${isPrimary == item.is_primary ? 'checked' : ''} />
                                                <label for="address-${key}">
                                                    <div class="summary-address">
                                                        <p>
                                                            ${item.building_name},
                                                            ${item.street_name},
                                                            ${item.city} ${item.pincode}.
                                                        </p>
                                                    </div>
                                                </label>
                                                <div class="address-delete">
                                                    <button type="button" class="dltBtn"
                                                        data-id=${item.id}>
                                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M2.25 4.5H3.75H15.75" stroke="black"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M6 4.5V3C6 2.60218 6.15804 2.22064 6.43934 1.93934C6.72065 1.65804 7.10218 1.5 7.5 1.5H10.5C10.8978 1.5 11.2794 1.65804 11.5607 1.93934C11.842 2.22064 12 2.60218 12 3V4.5M14.25 4.5V15C14.25 15.3978 14.092 15.7794 13.8107 16.0607C13.5294 16.342 13.1478 16.5 12.75 16.5H5.25C4.85218 16.5 4.47064 16.342 4.18934 16.0607C3.90804 15.7794 3.75 15.3978 3.75 15V4.5H14.25Z"
                                                                stroke="black" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M7.5 8.25V12.75" stroke="black"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M10.5 8.25V12.75" stroke="black"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="edit-address">
                                                    <button type="button" class="addressEdit" data-toggle="modal"
                                                        data-target="#modelAddress" data-id=${item.id}>
                                                        <div class="edit-ic">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M7.3335 2.66602H2.66683C2.31321 2.66602 1.97407 2.80649 1.72402 3.05654C1.47397 3.30659 1.3335 3.64573 1.3335 3.99935V13.3327C1.3335 13.6863 1.47397 14.0254 1.72402 14.2755C1.97407 14.5255 2.31321 14.666 2.66683 14.666H12.0002C12.3538 14.666 12.6929 14.5255 12.943 14.2755C13.193 14.0254 13.3335 13.6863 13.3335 13.3327V8.66602"
                                                                    stroke="#F58220" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M12.3335 1.66617C12.5987 1.40095 12.9584 1.25195 13.3335 1.25195C13.7086 1.25195 14.0683 1.40095 14.3335 1.66617C14.5987 1.93138 14.7477 2.29109 14.7477 2.66617C14.7477 3.04124 14.5987 3.40095 14.3335 3.66617L8.00016 9.9995L5.3335 10.6662L6.00016 7.9995L12.3335 1.66617Z"
                                                                    stroke="#F58220" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </div>
                                                        <div class="edit-txt">
                                                            <p>Edit</p>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                `);
                            });
                        }
                    }
                });
            }
            $('.add-address').on('click', function() {
                $('#address-title').html('Add Address');
                $('#address-form input').val('');
                $("#address-form input[name='_token']").val("{{ csrf_token() }}");
            });

            $(document).on('click', '.primary_address', function(e) {
                var address_id = $(this).data('id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                $.ajax({
                    url: "{{ route('user.change.primary.address') }}",
                    method: 'post',
                    data: {
                        address_id: address_id,
                    },
                    success: function(result) {}
                });
            });
            $(document).on('click', '.addressEdit', function(e) {
                $('#address-title').html('Edit Address');
                var address_id = $(this).data('id');
                $("input[name='address_id']").val(address_id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                $.ajax({
                    url: "{{ route('user.address.details') }}",
                    method: 'post',
                    data: {
                        address_id: address_id,
                    },
                    success: function(result) {
                        $("input[name='building_name']").val(result.building_name);
                        $("input[name='street_name']").val(result.street_name);
                        $("input[name='pincode']").val(result.pincode);
                        $("input[name='city']").val(result.city);
                    }
                });
            });
            $.validator.addMethod("regx", function(value, element, regexpr) {
                return regexpr.test(value);
            }, $.validator.messages.regx);
            $('#address-form').validate({
                rules: {
                    building_name: {
                        required: true
                    },
                    street_name: {
                        required: true
                    },
                    pincode: {
                        required: true,
                        regx: /^[1-9][0-9]{5}$/
                    },
                    city: {
                        required: true
                    }
                },
                messages: {
                    building_name: {
                        required: "The building name field is required."
                    },
                    street_name: {
                        required: "The street name field is required."
                    },
                    pincode: {
                        required: "The pincode field is required.",
                        regx: "The pincode field is not valid."
                    },
                    city: {
                        required: "The city field is required."
                    }
                }
            });
            $('#btn-save').click(function() {
                if ($('#address-form').valid()) {
                    $.ajax({
                        url: "{{ route('user.address.storeOrUpdate') }}",
                        method: 'post',
                        data: $('#address-form').serialize(),
                        success: function(response) {
                            reloadAddressList();
                            $('#modelAddress').modal('hide')
                            swal(response);
                        },
                        error: function(request, status, error) {
                            swal(request.responseText);
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.dltBtn', function(e) {
                var liObj = $(this).parents('li');
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
                            $.ajax({
                                url: "{{ route('user.address.destroy') }}",
                                method: 'post',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    _method: 'delete',
                                    address_id: dataID
                                },
                                success: function(response) {
                                    liObj.remove();
                                    swal(response);
                                },
                                error: function(request, status, error) {
                                    swal(request.responseText);
                                }
                            });
                        } else {
                            swal("Your data is safe!");
                        }
                    });
            })
        })
    </script>
@endpush
