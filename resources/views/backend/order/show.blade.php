@extends('backend.layouts.master')

@section('title', 'Order Detail')

@section('main-content')
    <div class="card">
        <h5 class="card-header">Order <a href="{{ route('order.pdf', $order->id) }}"
                class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i>
                Generate PDF</a>
        </h5>
        <div class="card-body">
            @if ($order)
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>Title</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    @php
                        $totalQty = 0;
                        $totalPice = 0;
                        $totalAmount = 0;
                    @endphp
                    <tbody>
                        @foreach ($order->cart_info as $key => $cart)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $cart->product->title }}</td>
                                <td>{{ $cart->quantity }}</td>
                                <td>{{ $cart->price }}</td>
                                <td>{{ $cart->amount }}</td>
                            </tr>
                            @php
                                $totalQty += $cart->quantity;
                                $totalPice += $cart->price;
                                $totalAmount += $cart->amount;
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Total</th>
                            <th>{{ $totalQty }}</th>
                            <th>{{ number_format($totalPice, 2) }}</th>
                            <th>{{ number_format($totalAmount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>

                <section class="confirmation_part section_padding">
                    <div class="order_boxes">
                        <div class="row">
                            <div class="col-lg-6 col-lx-4">
                                <div class="order-info">
                                    <h4 class="text-center pb-4">ORDER INFORMATION</h4>
                                    <table class="table">
                                        <tr class="">
                                            <td>Order Number</td>
                                            <td> : {{ $order->order_number }}</td>
                                        </tr>
                                        @php
                                            $orderDate = strtotime($order->created_at);
                                        @endphp
                                        <tr>
                                            <td>Order Date</td>
                                            <td> : {{ date('D d M, Y', $orderDate) }} at
                                                {{ date('g : i a', $orderDate) }} </td>
                                        </tr>
                                        <tr>
                                            <td>Order Status</td>
                                            <td> : {{ $order->status }}</td>
                                        </tr>
                                        <tr>
                                            <td>Sub Total Amount</td>
                                            <td> : Rs. {{ number_format($order->sub_total_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Points</td>
                                            <td> : {{ $order->points }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Amount</td>
                                            <td> : Rs. {{ number_format($order->total_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Payment Method</td>
                                            <td> : @if ($order->payment_method == 'cod')
                                                    Cash on Delivery
                                                @else
                                                    Razorpay
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Payment Status</td>
                                            <td> : {{ $order->payment_status }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

        </div>
    </div>
@endsection

@push('styles')
    <style>
        .order-info,
        .shipping-info {
            background: #ECECEC;
            padding: 20px;
        }

        .order-info h4,
        .shipping-info h4 {
            text-decoration: underline;
        }

    </style>
@endpush
