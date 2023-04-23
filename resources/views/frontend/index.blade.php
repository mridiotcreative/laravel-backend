@extends('frontend.layouts.master')
@section('title', __('page_title.home'))
@section('main-content')

<style>
    /* .singleProductCategory .owl-carousel .owl-item{
        width: 192px !important;
        margin-right: 0px !important;
    } */
</style>
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

    <!-- Start Most Popular -->
    <div class="product-area most-popular section shop-by-categories">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        {{-- <h2>Shop by Category</h2> --}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 singleProductCategory">
                    <div class="owl-carousel product-popular-slider">
                        @foreach ($product_lists as $product)
                            <div class="single-product-category single-product">
                                <div class="product-img-category">
                                    <a href="{{ route('product-detail', $product->slug) }}">
                                        <img class="default-img" src="{{ $product->photo[0] }}"
                                            alt="{{ $product->title }}">
                                    </a>
                                </div>
                                {{-- <div class="product-content">
                                    <h3><a
                                            href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a>
                                    </h3>
                                </div> --}}
                            </div>
                        @endforeach
                    </div>

                    <div class="owl-carousel popular-slider d-none">
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

    {{-- top deal section starts here --}}
    <div class="product-area most-popular section top-deal-main">
        <div class="container">
            {{-- <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Top Deals</h2>
                        <div class="view-all">
                            <a href="{{ route('product-grids') }}">View All</a>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="row"> --}}
                <div class="row single-index-product product_main_div">
                    {{-- <div class="owl-carousel popular-slider"> --}}
                        @foreach ($product_lists as $product)
                            <!-- Start Single Product -->
                            <div class="col-lg-4 col-md-6 col-12 product_row_div">
                                <div class="single-product">
                                    <div class="product-img">
                                        @php
                                            $newPhoto = '';
                                        @endphp
                                        <a href="{{ route('product-detail', $product->slug) }}">
                                            <img class="default-img" src="{{ $product->photo[0] }}"
                                                alt="{{ $product->title }}">
                                        </a>

                                    </div>
                                    <div class="product-content">
                                        <h3><a href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a></h3>
                                        @php
                                            $new_price = ($product->price_master_info != null) ? $product->price_master_info->special_price : (($product->offer_master_info != null)  ? $product->offer_master_info->special_price : $product->price);
                                        @endphp
                                        <div class="retail-prise">
                                            <span>Rs.{{ $new_price }}</span>
                                            <button data-id="{{ $product->slug }}" class="btn-add-to-cart second-add-to-cart-button">+</button>
                                        </div>
                                        <div class="button-head">
                                            <div class="product-action">
                                            </div>
                                            <div class="product-action-2">
                                                <button data-id="{{ $product->slug }}" class="btn-add-to-cart first-add-to-cart-button">Add to
                                                    cart</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                        @endforeach
                    {{-- </div> --}}
                </div>
            {{-- </div> --}}
        </div>
    </div>

    {{-- top deal section ends here --}}
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

        /* #Gslider .carousel-inner img {
            width: 100% !important;
            opacity: .8;
            height: 458px !important;
        } */

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
