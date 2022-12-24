@extends('frontend.layouts.master')
@section('title', __('page_title.change_password'))
@section('main-content')
    <section class="product-area shop-sidebar shop-list shop my-account-main">
        <div class="account-breadcrum">
            <div class="col-md-12">
                <div class="container">
                    <section class="my-account-title">
                        <h3 class="title">My Account</h3>
                        <i class="fa fa-angle-right"></i>
                        <h5 class="title">Change Password</h5>
                    </section>
                </div>

            </div>
        </div>
        <div class="container">

            <div class="row">

                <div class="col-lg-4 col-md-4 col-12">
                    @include('frontend.layouts.sidebar')
                </div>
                <div class="col-lg-8 col-md-8 col-12">
                    <div class="my-account-main-rignt">
                        <h4 class="text-left title change-pass">Change Password</h4>
                        <form method="POST" action="{{ route('user.change.password') }}" class="change-password-form"
                            id="change-password-form">
                            @csrf
                            <div class="form-group">
                                <label>Old Password <span>*</span></label>
                                <input type="password" autocomplete="off" name="old_password" id="old_password"
                                    maxlength="35" value="{{ old('old_password') }}" placeholder="Enter old paasword">
                                @error('old_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>New Password<span>*</span></label>
                                <input type="password" autocomplete="off" name="new_password" id="new_password"
                                    maxlength="35" value="{{ old('new_password') }}" placeholder="Enter new paasword">
                                @error('new_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Confirm Password<span>*</span></label>
                                <input type="password" autocomplete="off" name="confirm_password" id="confirm_password"
                                    maxlength="35" value="{{ old('confirm_password') }}"
                                    placeholder="Enter Confirm Password">
                                @error('confirm_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="update-password">
                                <button class="pass-update">Update</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('frontend/js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#change-password-form').validate({
                rules: {
                    old_password: {
                        required: true
                    },
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
                    old_password: {
                        required: "The old password field is required."
                    },
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
