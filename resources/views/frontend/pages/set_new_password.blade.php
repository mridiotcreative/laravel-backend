@extends('frontend.layouts.master')

@section('title', __('page_title.set_password'))

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
                                <h4>Set New Password</h4>
                            </div>
                        </div>
                        <!-- Form -->
                        <form class="form" method="post" id="reset-password-form"
                            action="{{ route('password.setNew.submit', [$token]) }}">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input id="new_password" type="password" name="new_password" placeholder=""
                                            required="required" value="{{ old('new_password') }}">
                                        @error('new_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" name="confirm_password" placeholder="" required="required"
                                            value="{{ old('confirm_password') }}">
                                        @error('confirm_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group login-btn">
                                        <button class="btn" type="submit">Submit</button>
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
            $('#reset-password-form').validate({
                rules: {
                    new_password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#new_password"
                    }
                },
                messages: {
                    new_password: {
                        required: "The new password field is required.",
                        minlength: "The new password must be at least 6 characters."
                    },
                    confirm_password: {
                        required: "The confirm password field is required.",
                        equalTo: "The confirm password must be equal to new password."
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
