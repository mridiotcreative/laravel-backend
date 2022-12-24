@extends('frontend.layouts.master')

@section('title', __('page_title.not_found'))

@section('main-content')
    <div class="container-fluid">
        <div class="row" style="margin-top:10%">
            <!-- 404 Error Text -->
            <div class="col-md-12">
                <div class="text-center">
                    <div class="error-main-wrapper">
                        <div class="error-image">
                            <img src="{{ url('frontend/img/404.svg') }}" alt="">
                        </div>
                        <div class="error-content">
                            <div class="error-head">
                                <h3> <span>404</span> Error</h3>
                            </div>
                            <div class="error-para">
                                <p>Sorry, that page doesn't found</p>
                            </div>
                            <div class="back-home-btn">
                                <a href="{{ route('home') }}">Home</a>
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
        .error-main-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10%
        }

        .error-head h3 {
            font-style: normal;
            font-weight: 300;
            font-size: 65px;
            line-height: 79px;
            text-transform: uppercase;
            color: #0E1316;
            font-family: 'Inter', sans-serif;
        }

        .error-image {
            margin-right: 31px;
        }

        .error-head h3 span {
            font-style: normal;
            font-weight: 900;
            font-size: 65px;
            line-height: 79px;
            color: #F58220;
            font-family: 'Inter', sans-serif;
        }

        .error-para p {
            font-style: normal;
            font-weight: 400;
            font-size: 18px;
            line-height: 22px;
            color: #0E1316;
            text-align: start;
            font-family: 'Inter', sans-serif;
        }

        .back-home-btn a {
            text-decoration: none;
            text-align: start;
            width: 126px;
            height: 46px;
            left: 700px;
            top: 552px;
            background: #005778;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 40px;
        }

        .back-home-btn a {
            font-style: normal;
            font-weight: 500;
            font-size: 14px;
            line-height: 17px;
            color: #FFFFFF;
            font-family: 'Inter', sans-serif;
        }

        @media only screen and (max-width: 767px) {
            .back-home-btn a {
                margin: 0 auto;
            }

            .error-main-wrapper {
                flex-direction: column
            }

            .error-image {
                margin-right: 0px;
            }

            .error-para p {
                text-align: center;
                margin-bottom: 20px;
                margin-top: 0;
                font-size: 17px;
            }

            .back-home-btn {
                margin: 0 auto
            }

            .error-head h3 span {
                font-size: 50px
            }

            .error-head h3 {
                font-size: 50px;
                margin-bottom: 0
            }

        }
    </style>
@endpush
