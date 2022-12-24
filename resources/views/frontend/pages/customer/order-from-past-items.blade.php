@extends('frontend.layouts.master')
@section('title', __('page_title.order_from_past_items'))
@section('main-content')
    <section class="product-area shop-sidebar shop-list shop my-account-main">
        <div class="account-breadcrum">
            <div class="col-md-12">
                <div class="container">
                    <section class="my-account-title">
                        <h3 class="title">My Account</h3>
                        <i class="fa fa-angle-right"></i>
                        <h5 class="title">Order From Past Items</h5>
                    </section>
                </div>

            </div>
        </div>
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-4 col-12">
                    @include('frontend.layouts.sidebar')
                </div>
                <div class="col-lg-9 col-md-8 col-12">
                    <div class="my-account-main-rignt order-past">
                        <h4 class="text-left title">Order From Past Items</h4>

                        <div class="order-from-past-item-main">
                            <div class="product-left-main">
                                <div class="product-main-image">
                                    <img src="/frontend/img/past-product.svg" alt="">
                                </div>
                                <div class="product-right-content">
                                    <div class="product-item-name">
                                        <p>Biogetica Aerosolve Cold & Cough Tablets ... </p>
                                    </div>
                                    <div class="total-product-past">
                                        <p>4 Tablet(s) in Strip</p>
                                    </div>
                                    <div class="prise-main">
                                        <div class="retail-prise">
                                            <p>Retail price*</p>
                                            <span>Rs.149.50</span>
                                        </div>
                                        <div class="cut-prise">
                                            <p>MRP Rs.299.00</p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="product-right-main">
                                <div class="add-cart-btn">
                                    <a href="#">View Product</a>
                                </div>
                            </div>
                        </div>
                        <div class="order-from-past-item-main">
                            <div class="product-left-main">
                                <div class="product-main-image">
                                    <img src="/frontend/img/past-product.svg" alt="">
                                </div>
                                <div class="product-right-content">
                                    <div class="product-item-name">
                                        <p>Biogetica Aerosolve Cold & Cough Tablets ... </p>
                                    </div>
                                    <div class="total-product-past">
                                        <p>4 Tablet(s) in Strip</p>
                                    </div>
                                    <div class="prise-main">
                                        <div class="retail-prise">
                                            <p>Retail price*</p>
                                            <span>Rs.149.50</span>
                                        </div>
                                        <div class="cut-prise">
                                            <p>MRP Rs.299.00</p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="product-right-main">
                                <div class="add-cart-btn">
                                    <a href="#">View Product</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
