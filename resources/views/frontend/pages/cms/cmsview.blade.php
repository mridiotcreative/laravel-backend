@extends('frontend.layouts.master')

@section('title', 'Home - '.$cms->title)

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Home<i class="fa fa-angle-right"></i></a></li>
                            <li class="active"><a href="{{ route('cms.page.view', $cms->slug) }}">{{ $cms->title }}</a></li>
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
                        <p>{{ strtoupper($cms->title) }}</p>
                    </div>
                    <div class="about-para-banner">
                        <p>OVERVIEW OF Valunova</p>
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
                            <h2>Welcome to ValuNova</h2>
                        </div>
                        <div class="about-para">
                            <p><?=$cms->description?></p>
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
