@extends('frontend.layouts.master')
@section('title', __('page_title.my_points'))
@section('main-content')
    <section class="product-area shop-sidebar shop-list shop my-account-main">
        <div class="account-breadcrum">
            <div class="col-md-12">
                <div class="container">
                    <section class="my-account-title">
                        <h3 class="title">My Account</h3>
                        <i class="fa fa-angle-right"></i>
                        <h5 class="title">My Points</h5>
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
                    <div class="my-account-main-rignt">
                        <div class="my-points-head">
                            <h4 class="text-left title">My Points</h4>
                        </div>
                        <div class="current-balance-head">
                            <p>Current Balance</p>
                        </div>
                        <div class="balance-point-head">
                            <p>{{ number_format($customer->total_points) }}</p>
                            <span>ValuNova Points</span>
                        </div>
                        <div class="how-to-earn-points">
                            <p>How to earn points</p>
                        </div>
                        <div class="earn-points-list">
                            <ul>
                                <li>Orders over Rs.500 receive 25 bonus reward points</li>
                                <!-- <li>Orders over Rs.500 receive 25 bonus reward points</li>
                                <li>Orders over Rs.500 receive 25 bonus reward points</li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
