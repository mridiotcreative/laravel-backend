@extends('frontend.layouts.master')
@section('title', __('page_title.home'))
@section('main-content')
    <!-- Slider Area -->
    @if (count($banners) > 0)
        <section id="Gslider" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                @foreach ($banners as $key => $banner)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img class="first-slide" src="{{ $banner->getImage() }}" alt="First slide">
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!--/ End Slider Area -->

    <!-- Start Small Banner  -->
    <section class="small-banner section">
        <div class="container">
            <div class="row">
                <!-- Single Banner  -->
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <a href="{{ route('user.order.history') }}">
                        <div class="single-banner">
                            <img src="/frontend/img/small1.svg" alt="">
                            <div class="content">
                                <p>Order </p>
                                <p>History</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- End Single Banner  -->
                <!-- Single Banner  -->
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <a href="{{ route('user.order.past.items') }}">
                        <div class="single-banner">
                            <img src="/frontend/img/small2.svg" alt="">
                            <div class="content">
                                <p>Order </p>
                                <p>from past items</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- End Single Banner  -->
                <!-- Single Banner  -->
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="single-banner">
                        <img src="/frontend/img/small3.svg" alt="">
                        <div class="content">
                            <p>Scan </p>
                            <p>your QR</p>
                        </div>
                    </div>
                </div>
                <!-- End Single Banner  -->
            </div>
        </div>
    </section>
    <!-- End Small Banner -->

    <!-- Start Most Popular -->
    <div class="product-area most-popular section shop-by-categories">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Shop by Category</h2>
                        <div class="view-all">
                            <a href="{{ route('product-grids') }}">View All</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="owl-carousel popular-slider">
                        @foreach ($product_lists as $product)
                            <!-- Start Single Product -->
                            <div class="single-product">
                                <div class="product-img">
                                    <a href="{{ route('product-detail', $product->slug) }}">
                                        <img class="default-img" src="{{ $product->photo[0] }}"
                                            alt="{{ $product->title }}">
                                        {{-- <span class="out-of-stock">Hot</span> --}}
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h3><a
                                            href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a>
                                    </h3>
                                    @php
                                        //$after_discount = $product->price - ($product->price * $product->discount) / 100;
                                        $new_price = ($product->price_master_info != null) ? $product->price_master_info->special_price : (($product->offer_master_info != null)  ? $product->offer_master_info->special_price : $product->price);
                                    @endphp
                                    <div class="retail-prise">
                                        <p>Retail price*</p>
                                        <span>Rs.{{ $new_price }}</span>

                                    </div>
                                    <div class="mrp-cut">
                                        <p>MRP RS.</p>
                                        <span class="old">{{ $product->price }}</span>
                                    </div>
                                    <div class="button-head">
                                        <div class="product-action">
                                        </div>
                                        <div class="product-action-2">
                                            <button class="btn-add-to-cart" data-id="{{ $product->slug }}">Add to
                                                cart</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- End Single Product -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Most Popular Area -->


    {{-- Earn Points section starts here --}}
    <div class="container">
        <div class="earn-points-main">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="earn-logo">
                        <img src="/frontend/img/earn-logo.svg" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="earn-points-head">
                        <h3>Earn up to <br />
                            <span>1000 Points</span>a month
                        </h3>
                        <div class="earn-points-para">
                            <p>* Terms and conditions applied</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2">
                    <div class="intensive-btn">
                        <button>Incentive </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Earn Points section ends here --}}

    {{-- top deal section starts here --}}
    <div class="product-area most-popular section top-deal-main">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Top Deals</h2>
                        <div class="view-all">
                            <a href="{{ route('product-grids') }}">View All</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="owl-carousel popular-slider">
                        @foreach ($product_lists as $product)
                            <!-- Start Single Product -->
                            <div class="single-product">
                                <div class="product-img">
                                    @php
                                        $newPhoto = '';
                                    @endphp
                                    <a href="{{ route('product-detail', $product->slug) }}">
                                        <img class="default-img" src="{{ $product->photo[0] }}"
                                            alt="{{ $product->title }}">
                                        {{-- <span class="out-of-stock">Hot</span> --}}
                                    </a>

                                </div>
                                <div class="product-content">
                                    <h3><a
                                            href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a>
                                    </h3>
                                    @php
                                        //$after_discount = $product->price - ($product->price * $product->discount) / 100;

                                        $new_price = ($product->price_master_info != null) ? $product->price_master_info->special_price : (($product->offer_master_info != null)  ? $product->offer_master_info->special_price : $product->price);
                                    @endphp
                                    <div class="retail-prise">
                                        <p>Retail price*</p>
                                        <span>Rs.{{ $new_price }}</span>

                                    </div>
                                    <div class="mrp-cut">
                                        <p>MRP RS.</p>
                                        <span class="old">{{ $product->price }}</span>
                                    </div>
                                    <div class="button-head">
                                        <div class="product-action">
                                        </div>
                                        <div class="product-action-2">
                                            <button data-id="{{ $product->slug }}" class="btn-add-to-cart">Add to
                                                cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- top deal section ends here --}}


    <div class="health-artical-main">
        <div class="container">
            <div class="health-article-head-top">
                <h1>Health Articles</h1>
                <div class="view-all">
                    <a href="{{ route('blogs') }}">See All</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="owl-carousel popular-slider blog-home-slider">
                        @foreach ($posts as $post)
                            <!-- Start Single Product -->
                            <div class="single-product">
                                <div class="article-body-main">
                                    <div class="article-img">
                                        <a href="{{ route('blog.detail', $post->slug) }}"><img
                                                src="{{ $post->photo[0] }}" alt=""></a>
                                    </div>
                                    <div class="article-time-main">
                                        <ul>
                                            <li>2 min read</li>
                                            <li class="date">{{ $post->created_at->format('M d, Y') }}</li>

                                        </ul>
                                    </div>
                                    <div class="health-article-head">
                                        <a href="{{ route('blog.detail', $post->slug) }}">
                                            <h4>{{ $post->title }}</h4>
                                        </a>
                                    </div>
                                    <div class="health-article-para">
                                        {!! $post->summary !!}
                                    </div>
                                    <div class="health-article-read">
                                        <a href="{{ route('blog.detail', $post->slug) }}">Read
                                            More</a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <script type='text/javascript'
        src='https://platform-api.sharethis.com/js/sharethis.js#property=5f2e5abf393162001291e431&product=inline-share-buttons'
        async='async'></script>
    <script type='text/javascript'
        src='https://platform-api.sharethis.com/js/sharethis.js#property=5f2e5abf393162001291e431&product=inline-share-buttons'
        async='async'></script>
    <style>
        /* Banner Sliding */
        #Gslider .carousel-inner {
            background: #000000;
            color: black;
        }

        #Gslider .carousel-inner {
            /* height: 550px; */
        }

        #Gslider .carousel-inner img {
            width: 100% !important;
            opacity: .8;
            height: 458px !important;
        }

        #Gslider .carousel-inner .carousel-caption {
            bottom: 60%;
        }

        #Gslider .carousel-inner .carousel-caption h1 {
            font-size: 50px;
            font-weight: bold;
            line-height: 100%;
            color: #F7941D;
        }

        #Gslider .carousel-inner .carousel-caption p {
            font-size: 18px;
            color: black;
            margin: 28px 0 28px 0;
        }

        #Gslider .carousel-indicators {
            bottom: 70px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        var $topeContainer = $('.isotope-grid');
        var $filter = $('.filter-tope-group');

        // filter items on button click
        $filter.each(function() {
            $filter.on('click', 'button', function() {
                var filterValue = $(this).attr('data-filter');
                $topeContainer.isotope({
                    filter: filterValue
                });
            });

        });

        // init Isotope
        $(window).on('load', function() {
            var $grid = $topeContainer.each(function() {
                $(this).isotope({
                    itemSelector: '.isotope-item',
                    layoutMode: 'fitRows',
                    percentPosition: true,
                    animationEngine: 'best-available',
                    masonry: {
                        columnWidth: '.isotope-item'
                    }
                });
            });
        });

        var isotopeButton = $('.filter-tope-group button');

        $(isotopeButton).each(function() {
            $(this).on('click', function() {
                for (var i = 0; i < isotopeButton.length; i++) {
                    $(isotopeButton[i]).removeClass('how-active1');
                }

                $(this).addClass('how-active1');
            });
        });
    </script>
    <script>
        function cancelFullScreen(el) {
            var requestMethod = el.cancelFullScreen || el.webkitCancelFullScreen || el.mozCancelFullScreen || el
                .exitFullscreen;
            if (requestMethod) { // cancel full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        function requestFullScreen(el) {
            // Supports most browsers and their versions.
            var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el
                .msRequestFullscreen;

            if (requestMethod) { // Native full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
            return false
        }
    </script>
@endpush
