@extends('frontend.layouts.master')
@section('title', __('page_title.order_summery'))
@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ 'home' }}">Home<i class="fa fa-angle-right"></i></a></li>
                            <li class="active"><a href="">Order Summery</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Shopping Cart -->
    <div class="shopping-cart section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="cart-head-main">
                        <div class="cart-head-txt">
                            <p>Order Summery </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-step-main">
                        <div class="card-steps">
                            <div class="my-cart-main active">
                                <div class="my-cart-img">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.091 18.3635C10.4424 18.3635 10.7273 18.0786 10.7273 17.7272C10.7273 17.3757 10.4424 17.0908 10.091 17.0908C9.7395 17.0908 9.45459 17.3757 9.45459 17.7272C9.45459 18.0786 9.7395 18.3635 10.091 18.3635Z"
                                            stroke="#005778" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M17.091 18.3635C17.4424 18.3635 17.7273 18.0786 17.7273 17.7272C17.7273 17.3757 17.4424 17.0908 17.091 17.0908C16.7395 17.0908 16.4546 17.3757 16.4546 17.7272C16.4546 18.0786 16.7395 18.3635 17.091 18.3635Z"
                                            stroke="#005778" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M5 5H7.54545L9.25091 13.5209C9.3091 13.8139 9.46849 14.0771 9.70117 14.2644C9.93384 14.4517 10.225 14.5512 10.5236 14.5455H16.7091C17.0077 14.5512 17.2989 14.4517 17.5316 14.2644C17.7642 14.0771 17.9236 13.8139 17.9818 13.5209L19 8.18182H8.18182"
                                            stroke="#005778" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="mycart-txt">
                                    <a href="{{ route('cart') }}">My Cart</a>
                                </div>
                            </div>
                            <div class="order-summary-main active">
                                <div class="order-summary-img">
                                    <svg width="18" height="22" viewBox="0 0 18 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.0657 20.9987H2.93357C2.31864 20.9979 1.72931 20.7533 1.29446 20.3185C0.859625 19.8836 0.61506 19.294 0.614258 18.6793V3.31931C0.615041 2.70438 0.859605 2.11506 1.29446 1.68021C1.7293 1.24537 2.31864 1.00081 2.93357 1H11.6292C12.2102 0.99896 12.768 1.22629 13.183 1.63269L16.7554 5.12939C17.1592 5.52168 17.3873 6.06016 17.3884 6.62289V18.6795C17.3876 19.2949 17.1425 19.8848 16.7071 20.3196C16.2715 20.7545 15.6812 20.9988 15.0657 20.9988L15.0657 20.9987ZM2.93357 1.94962C2.57025 1.94962 2.22183 2.09396 1.96471 2.35078C1.70788 2.60788 1.56354 2.95632 1.56354 3.31964V18.6797C1.56354 19.043 1.70788 19.3914 1.96471 19.6485C2.22181 19.9054 2.57025 20.0497 2.93357 20.0497H15.0657C15.4292 20.0497 15.7777 19.9054 16.0345 19.6485C16.2916 19.3914 16.4359 19.043 16.4359 18.6797V6.6231C16.4349 6.31642 16.312 6.02279 16.0943 5.80666L12.5216 2.32586C12.2838 2.09174 11.9631 1.96097 11.6293 1.96202L2.93357 1.94962Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M15.6001 7.00331H13.2933C12.7823 6.98765 12.2981 6.76997 11.9471 6.3983C11.596 6.02636 11.4065 5.53047 11.4201 5.01938V2.69352C11.4201 2.43121 11.6326 2.21875 11.8949 2.21875C12.1569 2.21875 12.3694 2.43121 12.3694 2.69352V5.01938C12.3564 5.27856 12.4461 5.53252 12.6189 5.72618C12.792 5.91984 13.0342 6.03782 13.2934 6.054H15.6001C15.8624 6.054 16.0749 6.26646 16.0749 6.52877C16.0749 6.79082 15.8624 7.00328 15.6001 7.00328L15.6001 7.00331Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M8.99841 4.67145H3.93545C3.6734 4.67145 3.46094 4.45899 3.46094 4.19668C3.46094 3.93463 3.6734 3.72217 3.93545 3.72217H8.99841C9.26072 3.72217 9.47318 3.93463 9.47318 4.19668C9.47318 4.45899 9.26072 4.67145 8.99841 4.67145Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M8.99841 6.56892H3.93545C3.6734 6.56892 3.46094 6.35645 3.46094 6.0944C3.46094 5.83209 3.6734 5.61963 3.93545 5.61963H8.99841C9.26072 5.61963 9.47318 5.83209 9.47318 6.0944C9.47318 6.35645 9.26072 6.56892 8.99841 6.56892Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M14.0616 10.0499H7.41667C7.15435 10.0499 6.94189 9.83741 6.94189 9.57536C6.94189 9.31305 7.15435 9.10059 7.41667 9.10059H14.0616C14.3239 9.10059 14.5364 9.31305 14.5364 9.57536C14.5364 9.83741 14.3239 10.0499 14.0616 10.0499Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M5.20131 10.0499H3.93545C3.6734 10.0499 3.46094 9.83741 3.46094 9.57536C3.46094 9.31305 3.6734 9.10059 3.93545 9.10059H5.20131C5.46336 9.10059 5.67608 9.31305 5.67608 9.57536C5.67608 9.83741 5.46336 10.0499 5.20131 10.0499Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M14.0616 12.5826H7.41667C7.15435 12.5826 6.94189 12.3701 6.94189 12.1081C6.94189 11.8458 7.15435 11.6333 7.41667 11.6333H14.0616C14.3239 11.6333 14.5364 11.8458 14.5364 12.1081C14.5364 12.3701 14.3239 12.5826 14.0616 12.5826Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M5.20131 12.5826H3.93545C3.6734 12.5826 3.46094 12.3701 3.46094 12.1081C3.46094 11.8458 3.6734 11.6333 3.93545 11.6333H5.20131C5.46336 11.6333 5.67608 11.8458 5.67608 12.1081C5.67608 12.3701 5.46336 12.5826 5.20131 12.5826Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M14.0616 15.4295H7.41667C7.15435 15.4295 6.94189 15.2168 6.94189 14.9548C6.94189 14.6927 7.15435 14.48 7.41667 14.48H14.0616C14.3239 14.48 14.5364 14.6927 14.5364 14.9548C14.5364 15.2168 14.3239 15.4295 14.0616 15.4295Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M5.20131 15.4295H3.93545C3.6734 15.4295 3.46094 15.2168 3.46094 14.9548C3.46094 14.6927 3.6734 14.48 3.93545 14.48H5.20131C5.46336 14.48 5.67608 14.6927 5.67608 14.9548C5.67608 15.2168 5.46336 15.4295 5.20131 15.4295Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M14.0616 17.961H7.41667C7.15435 17.961 6.94189 17.7485 6.94189 17.4865C6.94189 17.2244 7.15435 17.0117 7.41667 17.0117H14.0616C14.3239 17.0117 14.5364 17.2244 14.5364 17.4865C14.5364 17.7485 14.3239 17.961 14.0616 17.961Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                        <path
                                            d="M5.20131 17.961H3.93545C3.6734 17.961 3.46094 17.7485 3.46094 17.4865C3.46094 17.2244 3.6734 17.0117 3.93545 17.0117H5.20131C5.46336 17.0117 5.67608 17.2244 5.67608 17.4865C5.67608 17.7485 5.46336 17.961 5.20131 17.961Z"
                                            fill="#005778" stroke="#005778" stroke-width="0.5" />
                                    </svg>
                                </div>
                                <div class="order-txt">
                                    <a href="#">Order Summary</a>
                                </div>
                            </div>
                            <div class="order-payment">
                                <div class="payment-img">
                                    <svg width="20" height="14" viewBox="0 0 20 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.7499 0.125H1.24997C0.918548 0.125 0.60049 0.256808 0.366063 0.491227C0.131644 0.725646 0 1.04353 0 1.37513V12.6252C0 12.9568 0.131644 13.2747 0.366063 13.5091C0.600482 13.7435 0.918536 13.8752 1.24997 13.8752H18.7499C19.0815 13.8752 19.3994 13.7435 19.6338 13.5091C19.8682 13.2747 20 12.9568 20 12.6252V1.37513C20 1.04355 19.8682 0.725654 19.6338 0.491227C19.3994 0.256808 19.0815 0.125 18.7499 0.125ZM15.6812 12.6251H4.31877C4.18844 11.8587 3.82322 11.1514 3.27337 10.6018C2.72351 10.0519 2.01647 9.68673 1.24988 9.5564V4.44385C2.01647 4.31353 2.72355 3.9483 3.27337 3.39845C3.82322 2.8486 4.18844 2.14156 4.31877 1.37514H15.6812C15.8115 2.14156 16.1767 2.84864 16.7266 3.39845C17.2764 3.94826 17.9835 4.31352 18.7499 4.44385V9.5564C17.9835 9.68672 17.2764 10.0519 16.7266 10.6018C16.1767 11.1515 15.8115 11.8587 15.6812 12.6251ZM18.7499 3.16905C18.3188 3.05572 17.9255 2.83004 17.6105 2.5148C17.2952 2.19971 17.0695 1.80645 16.9562 1.37539H18.7499L18.7499 3.16905ZM3.04354 1.37535C2.93021 1.80641 2.70437 2.19971 2.38929 2.51476C2.07404 2.83001 1.68094 3.0557 1.24972 3.16901V1.37531L3.04354 1.37535ZM1.24967 10.8314C1.6809 10.9446 2.07403 11.1704 2.38925 11.4857C2.70433 11.8007 2.93019 12.194 3.0435 12.6251H1.24963L1.24967 10.8314ZM16.956 12.6251C17.0693 12.1941 17.295 11.8008 17.6103 11.4857C17.9253 11.1705 18.3186 10.9446 18.7497 10.8315V12.6252L16.956 12.6251ZM9.99963 2.62511C8.8394 2.62511 7.72656 3.08602 6.90617 3.90658C6.08561 4.72697 5.62469 5.83981 5.62469 7.00004C5.62469 8.16044 6.08561 9.27328 6.90617 10.0937C7.72656 10.9142 8.8394 11.3751 9.99963 11.3751C11.16 11.3751 12.2729 10.9142 13.0933 10.0937C13.9138 9.27328 14.3747 8.16044 14.3747 7.00004C14.3747 5.83981 13.9138 4.72697 13.0933 3.90658C12.2729 3.08602 11.16 2.62511 9.99963 2.62511ZM9.99963 10.125C9.17083 10.125 8.37603 9.79573 7.7899 9.20959C7.20394 8.62363 6.87466 7.82866 6.87466 6.99987C6.87466 6.17108 7.20394 5.37628 7.7899 4.79015C8.37603 4.20419 9.17083 3.87491 9.99963 3.87491C10.8284 3.87491 11.6234 4.20418 12.2093 4.79015C12.7955 5.37628 13.1248 6.17108 13.1248 6.99987C13.1248 7.82866 12.7955 8.62363 12.2093 9.20959C11.6234 9.79573 10.8284 10.125 9.99963 10.125Z"
                                            fill="#767676" />
                                    </svg>
                                </div>
                                <div class="payment-txt">
                                    <a href="#">Payment</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">

                    <div class="summary-main-section">
                        <div class="select-delivery-address-head">
                            <p>Select Delivery Address</p>
                        </div>
                        <form action="#">
                            <ul id="address-list">
                                @foreach ($addresses as $key => $address)
                                    <li>
                                        <div class="select-address-main">
                                            <div class="select-address-radio">
                                                <input type="radio" id="{{ $key }}" value="{{ $address->id }}"
                                                    name="address_id" class="address_id"
                                                    {{ $address->is_primary == config('constants.IS_PRIMARY') ? 'checked' : '' }}>
                                                <label for="{{ $key }}">
                                                    <div class="summary-address">
                                                        <p>{{ $address->building_name }},
                                                            {{ $address->street_name }},
                                                            {{ $address->city }}
                                                            {{ $address->pincode }}.</p>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </form>

                        <div class="change-address-main">
                            <div class="change-address-btn">
                                <button type="button" class="addressEdit" data-toggle="modal"
                                    data-target="#modelAddress">Change
                                    Address</button>
                            </div>
                            <div class="add-address-btn add-address">
                                <button type="button" data-toggle="modal" data-target="#modelAddress">Add
                                    Address</button>
                            </div>
                        </div>
                    </div>

                    @foreach (Helper::getAllProductFromCart() as $key => $cart)
                        <div class="my-cart-main-section">
                            <div class="cart-list-main">
                                <div class="cart-product">
                                    <img src="{{ $cart->product->photo[0] }}" alt="{{ $cart->product->title }}">
                                </div>
                                <div class="cart-product-detail-main">
                                    <div class="product-detail">
                                        <div class="product-detail-head">
                                            <h4><a href="{{ route('product-detail', 'slug') }}"
                                                    target="_blank">{{ $cart->product->title }}</a></h4>
                                        </div>
                                        <div class="product-prise-main">
                                            <div class="main-prise">
                                                <p><span>Rs.{{ number_format($cart->price, 2) }}</span></p>
                                            </div>
                                        </div>
                                        <div class="product-qty">
                                            <label>Qty: {{ $cart->quantity }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- End: New design for cart page --}}

                    {{-- </form> --}}

                </div>
                <div class="col-lg-6 col-md-12">

                    @php
                        $subTotalAmount = Helper::totalCartPrice();
                    @endphp

                    <!-- Total Amount -->
                    <div class="total-amount">

                        <div class="right">
                            <div class="payment-detail-main">
                                <p>Payment Details</p>
                            </div>
                            <ul>
                                <li class="order_subtotal" data-price="{{ $subTotalAmount }}">
                                    <p> MRP Total</p>
                                    <span>Rs.{{ number_format($subTotalAmount, 2) }}</span>
                                </li>

                                @php
                                    $points = 0;
                                    $totalAmount = $subTotalAmount;
                                    if (session()->has('points')) {
                                        $points = session('points');
                                        $totalAmount = $subTotalAmount - $points;
                                    }
                                @endphp

                                <div class="product-point-main">
                                    <p>Product Points</p>
                                    <span>-Rs.{{ $points }}</span>
                                </div>
                                <li class="last" id="order_total_price">Total
                                    Amount<span>Rs.{{ number_format($totalAmount, 2) }}</span></li>
                            </ul>

                        </div>
                        <div class="button5">
                            <a href="{{ route('checkout') }}" class="btn place-order">Make Payment</a>
                        </div>

                    </div>
                    <!--/ End Total Amount -->
                </div>
            </div>

        </div>
    </div>
    <!--/ End Shopping Cart -->
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
                                        <h1 id="address-title">Add Address</h1>
                                    </div>
                                    <form action="{{ route('user.address.storeOrUpdate') }}" method="post"
                                        id="address-form" autocomplete="off">
                                        @csrf
                                        <input type="hidden" id="address_id" name="address_id" />
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
                                            <button type="submit">
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
@push('styles')
    <style>
        li.shipping {
            display: inline-flex;
            width: 100%;
            font-size: 14px;
        }

        li.shipping .input-group-icon {
            width: 100%;
            margin-left: 10px;
        }

        .input-group-icon .icon {
            position: absolute;
            left: 20px;
            top: 0;
            line-height: 40px;
            z-index: 3;
        }

        .form-select {
            height: 30px;
            width: 100%;
        }

        .form-select .nice-select {
            border: none;
            border-radius: 0px;
            height: 40px;
            background: #f6f6f6 !important;
            padding-left: 45px;
            padding-right: 40px;
            width: 100%;
        }

        .list li {
            margin-bottom: 0 !important;
        }

        .list li:hover {
            background: #F7941D !important;
            color: white !important;
        }

        .form-select .nice-select::after {
            top: 14px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('frontend/js/nice-select/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("select.select2").select2();
        });
        $('select.nice-select').niceSelect();
    </script>
    <script>
        $(document).ready(function() {
            $('.shipping select[name=shipping]').change(function() {
                let cost = parseFloat($(this).find('option:selected').data('price')) || 0;
                let subtotal = parseFloat($('.order_subtotal').data('price'));
                let coupon = parseFloat($('.coupon_price').data('price')) || 0;
                // alert(coupon);
                $('#order_total_price span').text('$' + (subtotal + cost - coupon).toFixed(2));
            });

        });
    </script>
@endpush
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
                                                <input type="radio" id="${key}" value="${item.id}"
                                                    name="address_id" class="address_id"
                                                    ${isPrimary == item.is_primary ? 'checked' : ''}>
                                                <label for="${key}">
                                                    <div class="summary-address">
                                                        <p>
                                                            ${item.building_name},
                                                            ${item.street_name},
                                                            ${item.city} ${item.pincode}.
                                                        </p>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                `);
                            });
                        }
                    }
                });
            }
            // Add Address
            $('.add-address').on('click', function() {
                $('#address-title').html('Add Address');
                $('#address-form input').val('');
                $("#address-form input[name='_token']").val("{{ csrf_token() }}");
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
                },
                submitHandler: function(form) {
                    //form.submit();
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

            // Edit Address

            $(document).on('click', '.addressEdit', function(e) {
                $('#address-title').html('Edit Address');
                var address_id = $('.address_id:checked').val();
                $("input#address_id").val(address_id);
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
        });
    </script>
@endpush
