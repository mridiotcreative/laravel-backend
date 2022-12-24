@extends('frontend.layouts.master')
@section('title', __('page_title.order_history'))
@section('main-content')
<style>
    .product-history-main .cart-list-main {
        padding-bottom: 15px;
    }
</style>
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
                            <h4 class="text-left title">Order History</h4>
                            <div class="order-history-dropdown">
                                <select name="order_status" id="order_status">
                                    <option value="All">All</option>
                                    <!-- <option value="Completed">Completed</option> -->
                                    <option value="Pending">Pending</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Cancel">Cancel</option>
                                </select>
                            </div>
                        </div>

                        @foreach($orderData as $key => $order_data)
                        @php 
                            if($order_data->status == 'pending'){
                                $checkDatatype = 'Pending';
                            }
                            if($order_data->status == 'process'){
                                $checkDatatype = 'processing';
                            }
                            if($order_data->status == 'delivered'){
                                $checkDatatype = 'Delivered';
                            }
                            if($order_data->status == 'cancel'){
                                $checkDatatype = 'Cancel';
                            }
                        @endphp
                        <div class="order-status-main-section row_div" data-type="{{ $checkDatatype }}">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="order-status-left">
                                        <div class="order-status-head">
                                            <h4>Order Status</h4>
                                        </div>
                                        <div class="order-delivery-head">
                                            <h4>Delivered, {{ date('d M, Y', strtotime($order_data->created_at)) }}</h4>
                                        </div>
                                        @php 
                                            if($order_data->status == 'pending' || $order_data->status == 'process'){
                                                $statusClass = 'processing';
                                            }
                                            if($order_data->status == 'delivered'){
                                                $statusClass = 'completed';
                                            }
                                            if($order_data->status == 'cancel'){
                                                $statusClass = 'cancel';
                                            }
                                        @endphp
                                        <div class="order-completed {{ $statusClass }}">
                                            <p>{{ ucfirst($order_data->status) }} </p>
                                        </div>
                                        <div class="order-list-main">
                                            <ul>
                                                <div class="order-list-head">
                                                    <h5>Total Item {{ count($order_data->cart_info) }}</h5>
                                                </div>
                                                @foreach($order_data->cart_info as $key1 => $order_data_cart)
                                                    <li>{{ $order_data_cart->product->title }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="order-status-right">
                                        <div class="order-total">
                                            <p>Total </p>
                                        </div>
                                        <div class="order-total-amount">
                                            <p>₹ {{ number_format($order_data->total_amount, 2) }}</p>
                                        </div>
                                        <div class="order-view-detail" data-toggle="modal" data-target="#modelOrderHistory">
                                            <button class="view-detail-order view_order_data" data-id="{{ $order_data->id }}">View Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="modelOrderHistory" tabindex="-1" role="dialog">
        <div class="modal-dialog cart-history-model" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            class="ti-close" aria-hidden="true"></span></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="order-history-popup">
                                    <div class="order-number">
                                        <p>Order No: </p>
                                    </div>
                                    <div class="custom-checkbox-main">
                                        <div class="Checkbox">
                                            <input type="checkbox" checked />
                                            <div class="Checkbox-visible"></div>
                                        </div>
                                        <div class="check-label">
                                            <p>Order Delivered</p>
                                        </div>
                                    </div>
                                    <div class="total-item">
                                        <p>Item (2)</p>
                                    </div>
                                    <div class="product-history-main">
                                        <div class="my-cart-main-section">
                                        </div>
                                    </div>

                                    <div class="bill-detail-main">
                                        <div class="right">
                                            <div class="payment-detail-main">
                                                <p>Bill Details</p>
                                            </div>
                                            <ul>
                                                <li class="order_subtotal" data-price="">
                                                    <p> MRP Total</p> <span></span>
                                                </li>


                                                <div class="product-point-main">
                                                    <p>Product Points</p>
                                                    <span>-</span>
                                                </div>
                                                <li class="last" id="order_total_price">Total
                                                    <span></span>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
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
    <script>
        $(document).ready(function() {
            $(document).on('click', '.view_order_data', function(e) {
                var id = $(this).attr('data-id');
                $('.my-cart-main-section').html('');
                $.ajax({
                    url: "{{ route('user.order.details.by.id') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.key == 1) {
                            $('.order_subtotal').html('<p> MRP Total</p> <span>RS '+res.data.sub_total_amount+'</span>');
                            $('.product-point-main').html('<p> Product Points</p> <span>-RS '+res.data.points+'</span>');
                            $('#order_total_price').html('<p> Total</p> <span>RS '+res.data.total_amount+'</span>');
                            $('.order-number').html('<p>Order No: '+res.data.order_number+'</p>');

                            if(res.data.status == 'pending' || res.data.status == 'process'){
                                $statusClass = 'processing';
                            }
                            if(res.data.status == 'delivered'){
                                $statusClass = 'completed';
                            }
                            if(res.data.status == 'cancel'){
                                $statusClass = 'cancel';
                            }

                            $('.check-label').html('<p>Order '+res.data.status.charAt(0).toUpperCase() + res.data.status.slice(1)+'</p>');

                            
                            $('.total-item').html('<p>Item ('+res.data.cart_info.length+')</p>');

                            var cartHtml = "";
                            $.each(res.data.cart_info, function (i, val) {
                                console.log(val);

                                cartHtml += '<div class="cart-list-main">\
                                    <div class="cart-product">\
                                        <img src="'+val.product.photo+'" alt="'+val.product.photo+'">\
                                    </div>\
                                    <div class="cart-product-detail-main">\
                                        <div class="product-detail">\
                                            <div class="product-detail-head-history">\
                                                <h4><a href="'+val.product.url+'" target="_blank">'+val.product.title.slice(0, 20).concat('...')+'</a></h4>\
                                            </div>\
                                            <div class="stock-main">\
                                                <p>Instock</p>\
                                            </div>\
                                            <div class="product-prise-main-history">\
                                                <div class="all-prise">\
                                                    <div class="main-prise">\
                                                        <p><span>Rs. '+val.price+'</span></p>\
                                                    </div>\
                                                    <div class="cut-prise">\
                                                        <p><span class="money">Rs. '+val.product.price+'</span></p>\
                                                    </div>\
                                                </div>\
                                                <div class="total-quantity">\
                                                    <p>Qty: '+val.quantity+'</p>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>';

                            });

                            $('.my-cart-main-section').html(cartHtml);
                        }
                    }
                });
            });

            $("#order_status").change(function() {
                var filterValue = $(this).val();
                var row = $('.row_div'); 
                
                row.hide()
                row.each(function(i, el) {
                    if($(el).attr('data-type') == filterValue) {
                        $(el).show();
                    }
                    if(filterValue == 'All') {
                        $(el).show();
                    }
                })
                
            });
        });
    </script>
@endpush
