@extends('frontend.layouts.master')

@section('title', __('page_title.about_us'))

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Home<i class="fa fa-angle-right"></i></a></li>
                            <li class="active"><a href="blog-single.html">About Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- About Us -->

    <div class="about-us-main">
        <div class="about-banner-main">
            <div class="about-banner-img">
                <img src="/frontend/img/about.svg" alt="">
            </div>
            <div class="container">
                <div class="about-txt-main">
                    <div class="about-txt-head">
                        <p>ABOUT US</p>
                    </div>
                    <div class="about-para-banner">
                        <p>OVERVIEW OF Mridiot</p>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="about-welcome-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-banner-left">
                        <img src="/frontend/img/about-left.svg" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-right">
                        <div class="about-head">
                            <h2>Welcome to Mridiot</h2>
                        </div>
                        <div class="about-para">
                            <p>Mridiot Lifesciences Private Limited is a 1 year 9 months old Private Company
                                incorporated
                                on 01 Jul 2020. Its registered office is in Ahmedabad, Gujarat, india. <br />
                                The Company's status is Active, and it has filed its Annual Returns and Financial
                                Statements
                                up to 31 Mar 2021 (FY 2020-2021). It's a company limited by shares having an authorized
                                capital of Rs 5.00 cr and a paid-up capital of Rs 2.26 cr as per MCA.<br />
                                2 Directors are associated with the organization. Anilkumar Kishorilal Khemka and Nishu
                                Krishnakumar Agrawal are presently associated as directors.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="about-why-us-main">

        <div class="container">
            <div class="why-us-main">
                <div class="why-us-head">
                    <h1>Why us?</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="our-vision-main">
                        <div class="our-vision-ic">
                            <img src="frontend/img/vision.svg" alt="">
                        </div>
                        <div class="our-vision-head">
                            <h3>Our Vision</h3>
                        </div>
                        <div class="our-vision-para">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="our-vision-main">
                        <div class="our-vision-ic">
                            <img src="frontend/img/mission.svg" alt="">
                        </div>
                        <div class="our-vision-head">
                            <h3>Our Mission</h3>
                        </div>
                        <div class="our-vision-para">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="our-vision-main">
                        <div class="our-vision-ic">
                            <img src="frontend/img/value.svg" alt="">
                        </div>
                        <div class="our-vision-head">
                            <h3>Our Values</h3>
                        </div>
                        <div class="our-vision-para">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="empty-div">

    </div>

@endsection
