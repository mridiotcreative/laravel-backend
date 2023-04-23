@extends('frontend.layouts.master')

@section('title', __('page_title.login'))

@section('main-content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<!-- Shop Login -->
<section class="shop login section">
    <div class="container-fluid">
        <div class="row">
            {{-- <div class="col-lg-6 login-left-main">
                <div class="login-left-img">
                    <img src="/frontend/img/login-left.svg" alt="">
                </div>
            </div> --}}
            <div class="d-flex justify-content-center col-lg-12 col-12 login-form-main">
                <div class="login-form">
                    <div class="login-head-main">
                        {{-- <div class="login-head">
                            <h4>LOGIN</h4>
                        </div>
                        <div class="login-para">
                            <h3>Hello! Let’s get started</h3>
                        </div> --}}
                        <div class="login-para d-flex justify-content-center">
                            <h4>LOGIN</h4>
                        </div>
                    </div>
                    <!-- Form -->
                    <form class="form" id="loginForm" method="post" action="{{ route('login.submit') }}">
                        <div class="custom-retailer-mr-main d-none">
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

                            <div class="col-lg-12 input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
                                </div>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                        placeholder="Enter email">
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-12 input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                </div>
                                <input type="password" name="password" id="pass_log_id" class="form-control" placeholder="Enter password"
                                        value="{{ old('password') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-eye-slash toggle-password"></i></span>
                                </div>
                            </div>
                            <div class="col-12">
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="errorTxt"></div>
                            @if (Route::has('password.forgot'))
                            <a class="lost-pass" href="{{ route('password.forgot') }}">
                                Forgot password?
                            </a>
                            @endif
                            <div class="col-12">
                                <div class="form-group login-btn">
                                    <button class="btn" type="submit">Sign In</button>
                                </div>
                                <div class="create-account-main">
                                    <div class="create-account">
                                        <p>Not a member? <span> <a href="{{ route('register.form') }}">Register now</a>
                                            </span>
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
            errorElement : 'lable',
            errorLabelContainer: '.errorTxt',
            submitHandler: function(form) {
                form.submit();
            }
        });

        $("body").on('click', '.toggle-password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#pass_log_id");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    });
</script>
@endpush
