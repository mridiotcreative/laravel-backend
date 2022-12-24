@extends('frontend.layouts.master')
@section('title', __('page_title.my_profile'))
@section('main-content')
    <section class="product-area shop-sidebar shop-list shop my-account-main">
        <div class="account-breadcrum">
            <div class="col-md-12">
                <div class="container">
                    <section class="my-account-title">
                        <h3 class="title">My Account</h3>
                        <i class="fa fa-angle-right"></i>
                        <h5 class="title">My Profile</h5>
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
                        <div class="profile-head-main">
                            <h4 class="text-left title">My Profile</h4>
                        </div>
                        <form method="POST" class="contact-information-form" id="contact-information-form"
                            action="{{ route('user.profile.update') }}">
                            @csrf
                            <div class="form-group">
                                <label>User Type <span class="text-danger">*</span></label>
                                <input type="text" autocomplete="off" name="role_slug" id="role_slug"
                                    value="{{ $customer->roles->first()->name }}" disabled>
                                {{-- <select name="role_slug" id="role_slug">
                                    <option value="">Choose user type</option>
                                    @if (isset($roles))
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->slug }}"
                                                {{ (!empty($customer) && !empty($customer->roles->where('id', $role->id)->first())) || old('role_slug') == $role->slug ? 'selected' : '' }}>
                                                {{ $role->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('role_slug')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror --}}
                            </div>
                            <div class="form-group">
                                <label>Full Name<span class="text-danger">*</span></label>
                                <input type="text" autocomplete="off" name="full_name" id="full_name"
                                    value="{{ $customer->full_name }}">
                                @error('full_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Firm Name<span class="text-danger">*</span></label>
                                <input type="text" autocomplete="off" name="firm_name" id="firm_name"
                                    value="{{ $customer->firm_name }}">
                                @error('firm_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group email-read-only">
                                <label>Email<span class="text-danger">*</span></label>
                                <input type="email" autocomplete="off" name="email" id="email"
                                    value="{{ $customer->email }}" disabled>
                                <span class="notedit">Not Editable </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>State<span class="text-danger">*</span></label>
                                        <select name="state_id" id="state_id" class="form-control">
                                            <option value="">Choose State </option>
                                            @if (isset($state))
                                                @foreach ($state as $st)
                                                    <option value="{{ $st->id }}"
                                                        {{ (isset($customer) && $customer->state_id == $st->id) || old('state_id') == $st->id ? 'selected' : '' }}>
                                                        {{ $st->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('state_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>City<span class="text-danger">*</span></label>
                                        <select name="city_id" id="city_id" class="form-control">
                                            @if (isset($city))
                                                @foreach ($city as $ct)
                                                    <option value="{{ $ct->id }}"
                                                        {{ (isset($customer) && $customer->city_id == $ct->id) || old('city_id') == $ct->id ? 'selected' : '' }}>
                                                        {{ $ct->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">Choose City </option>
                                            @endif
                                        </select>
                                        @error('city_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Contact Number 1<span class="text-danger">*</span></label>
                                        <input type="number" autocomplete="off" name="contact_no_1" id="contact_no_1"
                                            value="{{ $customer->contact_no_1 }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Contact Number 2<span class="text-danger">*</span></label>
                                        <input type="number" autocomplete="off" name="contact_no_2" id="contact_no_2"
                                            value="{{ $customer->contact_no_2 }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Pincode<span class="text-danger">*</span></label>
                                        <input type="number" autocomplete="off" name="pincode" id="Pincode"
                                            value="{{ $customer->pincode }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 in_mr">
                                    <div class="form-group">
                                        <label>Designation<span class="text-danger">*</span></label>
                                        <input type="text" autocomplete="off" name="designation" id="designation"
                                            value="{{ $customer->designation }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group gst-main not_in_mr">
                                <label>GST No.<span class="text-danger">*</span></label>
                                <input type="text" autocomplete="off" name="FullName" id="FullName"
                                    value="{{ $customer->gst_no }}" disabled>
                                <div class="gst-active-main">
                                    <button type="button"
                                        onclick="popitup('{{ $customer->getGstDocument() }}','GST Document')">View</button>
                                </div>

                            </div>
                            <div class="form-group gst-main not_in_mr">
                                <label>Drug Licence No.<span class="text-danger">*</span></label>
                                <input type="text" autocomplete="off" name="DrugLicence" id="DrugLicence"
                                    value="{{ $customer->drug_licence_no }}" disabled>
                                <div class="gst-active-main">
                                    <button type="button"
                                        onclick="popitup('{{ $customer->getDrugDocument() }}','Drug Document')">View</button>
                                </div>
                            </div>
                            <div class="form-group gst-main">
                                <label>Document ( ID Proof )<span class="text-danger">*</span></label>
                                <input type="text" autocomplete="off" name="Document" id="Document" value=""
                                    placeholder="Document Upload" disabled>
                                <div class="gst-active-main">
                                    <button type="button"
                                        onclick="popitup('{{ $customer->getIdProofDocument() }}','Id Proof Document')">View</button>
                                </div>

                            </div>
                            <div class="form-group mb-3 mt-5 float-right">
                                <button class="edit-btn float-right">Update</button>
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
            $.validator.addMethod("regx", function(value, element, regexpr) {
                return regexpr.test(value);
            }, $.validator.messages.regx);
            $('#contact-information-form').validate({
                rules: {
                    full_name: {
                        required: true
                    },
                    firm_name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    pincode: {
                        required: true,
                        regx: /^[1-9][0-9]{5}$/
                    },
                    contact_no_1: {
                        required: true,
                        regx: /^(\+\d{1,3}[- ]?)?\d{10}$/
                    },
                    contact_no_2: {
                        required: true,
                        regx: /^(\+\d{1,3}[- ]?)?\d{10}$/
                    },
                    designation: {
                        required: true
                    },
                    gst_no: {
                        required: true,
                        //regx: /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/
                    },
                    gst_document: {
                        required: true
                    },
                    drug_licence_no: {
                        required: true
                    },
                    drug_document: {
                        required: true
                    },
                    id_proof_document: {
                        required: true
                    }
                },
                messages: {
                    full_name: {
                        required: "The full name field is required."
                    },
                    firm_name: {
                        required: "The firm name field is required."
                    },
                    email: {
                        required: "The email field is required."
                    },
                    password: {
                        required: "The password field is required.",
                        minlength: "The password must be at least 6 characters."
                    },
                    pincode: {
                        required: "The pincode field is required.",
                        regx: "The pincode field is not valid."
                    },
                    contact_no_1: {
                        required: "The contact no 1 field is required.",
                        regx: "The contact no 1 field is not valid."
                    },
                    contact_no_2: {
                        required: "The contact no 2 field is required.",
                        regx: "The contact no 2 field is not valid."
                    },
                    designation: {
                        required: "The designation field is required."
                    },
                    gst_no: {
                        required: "The GST no field is required.",
                        regx: "The GST no field is not valid."
                    },
                    gst_document: {
                        required: "The GST document field is required."
                    },
                    drug_licence_no: {
                        required: "The drug licence no field is required."
                    },
                    drug_document: {
                        required: "The drug document field is required."
                    },
                    id_proof_document: {
                        required: "The id proof document field is required."
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
    <script>
        function popitup(url, windowName) {
            window.open(url, '_blank');
            // window.open(url, windowName, 'height=500,width=500');
        }
        hideShowMr();
        // User Type
        $('#role_slug').change(function() {
            hideShowMr();
        });

        // Hide/Show MR
        function hideShowMr() {
            slug = $('#role_slug').val();
            if (slug == 'mr') {
                $('.not_in_mr').hide();
                $('.in_mr').show();
            } else {
                $('.not_in_mr').show();
                $('.in_mr').hide();
            }
        }

        // Get City based on state
        $('#state_id').change(function() {
            getCity($(this).val());
        });

        function getCity(state_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
            $.ajax({
                url: "{{ route('register.city') }}",
                method: 'post',
                data: {
                    state_id: state_id,
                },
                success: function(result) {
                    if (result.result.length > 0) {
                        $('#city_id').empty();
                        $.each(result.result, function(i, item) {
                            $('#city_id').append($("<option></option>")
                                .attr("value", item.id)
                                .text(item.name));
                            $('#city_id').niceSelect('update');
                        });
                    }
                }
            });
        }
    </script>
@endpush
