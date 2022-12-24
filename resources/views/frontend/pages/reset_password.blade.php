@extends('frontend.layouts.master')

@section('title', __('page_title.reset_password'))

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
                                <h4>Forgot Password</h4>
                            </div>
                            <div class="login-para">
                                <h3>Enter the email address associated with your account</h3>
                            </div>
                        </div>
                        <!-- Form -->
                        <form class="form" id="reset-link-form" method="post"
                            action="{{ route('password.forgot.submit') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Your Email</label>
                                        <input type="email" name="email" placeholder="" required="required"
                                            value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group login-btn">
                                        <button class="btn" type="submit">Send Password Reset Link</button>
                                    </div>
                                    <div class="create-account-main">
                                        <div class="create-account">
                                            <p>Already have an account? <span> <a href={{ route('login.form') }}>Log
                                                        In</a> </span>
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
            $('#reset-link-form').validate({
                rules: {
                    email: {
                        required: true
                    }
                },
                messages: {
                    email: {
                        required: "The email field is required."
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
