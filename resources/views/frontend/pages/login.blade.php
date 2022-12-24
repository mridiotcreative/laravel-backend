@extends('frontend.layouts.master')

@section('title', __('page_title.login'))

@section('main-content')


    <!-- Shop Login -->
    <section class="shop login section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 login-left-main">
                    <div class="login-left-img">
                        <img src="/frontend/img/login-left.svg" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-12 login-form-main">
                    <div class="login-form">
                        <div class="login-head-main">
                            <div class="login-head">
                                <h4>LOGIN</h4>
                            </div>
                            <div class="login-para">
                                <h3>Hello! Let’s get started</h3>
                            </div>
                        </div>
                        <!-- Form -->
                        <form class="form" id="loginForm" method="post" action="{{ route('login.submit') }}">
                            <div class="custom-retailer-mr-main">
                                {{-- <form action="#"> --}}
                                <p>
                                    <input type="radio" id="test1" name="role_type" value="1" checked>
                                    <label for="test1">Stockist / Retailer / Institute / Doctor</label>
                                </p>
                                <p class="mr">
                                    <input type="radio" id="test2" name="role_type" value="2">
                                    <label for="test2">MR</label>
                                </p>
                                @error('role_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Your Email</label>
                                        <input type="email" name="email" placeholder="" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Your Password</label>
                                        <input type="password" name="password" placeholder=""
                                            value="{{ old('password') }}">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group login-btn">
                                        <button class="btn" type="submit">Login</button>
                                    </div>
                                    @if (Route::has('password.forgot'))
                                        <a class="lost-pass" href="{{ route('password.forgot') }}">
                                            Lost your password?
                                        </a>
                                    @endif
                                    <div class="create-account-main">
                                        <div class="create-account">
                                            <p>Don’t have an account yet? <span> <a
                                                        href="{{ route('register.form') }}">Create an account</a> </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--/ End Form -->
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!--/ End Login -->
@endsection
@push('styles')
    <style>
        .custom-retailer-mr-main {
            display: flex;
        }

        .shop.login .form .btn {
            margin-right: 0;
        }

        .btn-facebook {
            background: #39579A;
        }

        .btn-facebook:hover {
            background: #073088 !important;
        }

        .btn-github {
            background: #444444;
            color: white;
        }

        .btn-github:hover {
            background: black !important;
        }

        .btn-google {
            background: #ea4335;
            color: white;
        }

        .btn-google:hover {
            background: rgb(243, 26, 26) !important;
        }

    </style>
@endpush
@push('scripts')
    <script src="{{ asset('frontend/js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#loginForm').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    email: {
                        required: "The email field is required."
                    },
                    password: {
                        required: "The password field is required.",
                        minlength: "The password must be at least 6 characters."
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
