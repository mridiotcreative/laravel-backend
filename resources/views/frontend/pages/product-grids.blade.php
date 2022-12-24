@extends('frontend.layouts.master')

@section('title', __('page_title.product'))

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Home<i class="fa fa-angle-right"></i></a></li>
                            <li class="active"><a href="blog-single.html">Shop Grid</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Product Style -->
    <form action="{{ url()->full() }}" method="POST">
        @csrf
        <section class="product-area shop-sidebar shop section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="shop-sidebar">
                            <!-- Single Widget -->
                            @if(empty($_GET['category']))
                            <div class="single-widget category">
                                <div class="categorie-main">
                                    <div class="categorie-img">
                                        <img src="/frontend/img/categorie-ic.svg" alt="">
                                    </div>
                                    <h3 class="title">Categories</h3>
                                </div>
                                <div class="product-drop-main">
                                    <select name="categories" id="categories">
                                        <option value="All">-- Show All --</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-12">
                        <div class="row">
                            <div class="col-12">
                                <!-- Shop Top -->
                                <div class="shop-top">
                                    <div class="shop-search">
                                        @if (request()->input('search'))
                                            <label>Search Results For: '{{ request()->input('search') }}'</label>
                                        @endif
                                    </div>
                                    <div class="shop-shorter">
                                        <div class="single-shorter">
                                            <label>Sort By :</label>
                                            <select class='sortBy' name='sortBy'>
                                                <option value="default">Default</option>
                                                <option value="price_high"> Price: (High To Low)</option>
                                                <option value="price_low"> Price: (Low To High)</option>
                                                <option value="title"> Name </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Shop Top -->
                            </div>
                        </div>
                        <div class="row product_main_div">
                            @if (count($products) > 0)
                                @foreach ($products as $product)

                                    @php
                                        //$after_discount = $product->price - ($product->price * $product->discount) / 100;
                                        $new_price = ($product->price_master_info != null) ? $product->price_master_info->special_price : (($product->offer_master_info != null)  ? $product->offer_master_info->special_price : $product->price);  
                                    @endphp
                                    <div class="col-lg-4 col-md-6 col-12 product_row_div" data-type="{{ $product->cat_id }}" data-price="{{ intval(preg_replace('/[^\d.]/', '', $new_price)) }}" data-slug="{{ $product->slug }}">
                                        <div class="single-product">
                                            <div class="product-img">
                                                
                                                <a href="{{ route('product-detail', $product->slug) }}">
                                                    <img class="default-img" src="{{ $product->photo[0] }}"
                                                        alt="{{ $product->title }}">
                                                    @if ($product->discount)
                                                        <span class="price-dec">{{ $product->discount }} %
                                                            Off</span>
                                                    @endif
                                                </a>

                                            </div>
                                            <div class="product-content">
                                                <h3>
                                                    <a href="{{ route('product-detail', $product->slug) }}">
                                                        {{ $product->title }}
                                                    </a>
                                                </h3>
                                                <div class="retail-prise-main">
                                                    <div class="retail-head">
                                                        <p>Retail price*</p>
                                                    </div>
                                                    <span>Rs. {{ $new_price }} </span>
                                                </div>
                                                <div class="mrp-price-main">
                                                    <div class="mrp-head">
                                                        <p>MRP Rs.</p>
                                                    </div>
                                                    <del style="padding-left:4%;">{{ $product->price }}
                                                    </del>
                                                </div>
                                                <div class="button-head">
                                                    <div class="product-action-2">
                                                        <button type="button" title="Add to cart"
                                                            data-id="{{ $product->slug }}" class="btn-add-to-cart">Add to
                                                            cart</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h4 class="text-warning" style="margin:100px auto;">There are no products.</h4>
                            @endif
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-12 justify-content-center d-flex">
                                {{ $products->appends($_GET)->links() }}
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </section>
    </form>

    <!--/ End Product Style 1  -->

@endsection
@push('styles')
    <style>
        .pagination {
            display: inline-flex;
        }

        .filter_button {
            /* height:20px; */
            text-align: center;
            background: #F7941D;
            padding: 8px 16px;
            margin-top: 10px;
            color: white;
        }

        .shop-top .nice-select.sortBy {
            width: 189px !important;
        }

    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            /*----------------------------------------------------*/
            /*  Jquery Ui slider js
            /*----------------------------------------------------*/
            if ($("#slider-range").length > 0) {
                const max_value = parseInt($("#slider-range").data('max')) || 500;
                const min_value = parseInt($("#slider-range").data('min')) || 0;
                const currency = $("#slider-range").data('currency') || '';
                let price_range = min_value + '-' + max_value;
                if ($("#price_range").length > 0 && $("#price_range").val()) {
                    price_range = $("#price_range").val().trim();
                }

                let price = price_range.split('-');
                $("#slider-range").slider({
                    range: true,
                    min: min_value,
                    max: max_value,
                    values: price,
                    slide: function(event, ui) {
                        $("#amount").val(currency + ui.values[0] + " -  " + currency + ui.values[1]);
                        $("#price_range").val(ui.values[0] + "-" + ui.values[1]);
                    }
                });
            }
            if ($("#amount").length > 0) {
                const m_currency = $("#slider-range").data('currency') || '';
                $("#amount").val(m_currency + $("#slider-range").slider("values", 0) +
                    "  -  " + m_currency + $("#slider-range").slider("values", 1));
            }

            $("#categories").change(function() {
                var filterValue = $(this).val();
                var row = $('.product_row_div'); 
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

            $(".sortBy").change(function() {
                let newDom = new Array();
                var row = $('.product_row_div');
                var filterValue = $(this).val();

                if (filterValue == 'price_high' || filterValue == 'price_low' || filterValue == 'title') {
                    row.each(function(i, el) {
                        let price = parseInt($(el).attr('data-price'));
                        let slug = $(el).attr('data-slug');
                        let changeArray = new Array();
                        changeArray['htmlNode'] = el;
                        changeArray['price'] = price;
                        changeArray['slug'] = slug;
                    });

                    if (filterValue == 'price_high') {
                        newDom.sort(function(a, b) {
                            return b.price - a.price;
                        });
                    }
                    if (filterValue == 'price_low') {
                        newDom.sort(function(a, b) {
                            return a.price - b.price;
                        });  
                    }

                    if (filterValue == 'title') {
                        newDom.sort(function(a, b) {
                            if(a.slug < b.slug) { return -1; }
                            if(a.slug > b.slug) { return 1; }
                            return 0;
                        });  
                    }
                    
                    $(newDom).each(function(index, node) {
                        $('.product_main_div').append(newDom[index].htmlNode);
                    });
                }

                if (filterValue == 'default') {
                    row.show()
                }
            });
        })
    </script>
@endpush
