@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">
            @if (isset($customer))
                Edit Customer
            @else
                Add Customer
            @endif
        </h5>
        <div class="card-body">
            <form method="post" action="{{ route('customer.update', isset($customer->id) ? $customer->id : null) }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($customer))
                    @method('PATCH')
                @endif
                {{-- User Type & Firm Name --}}
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>User Type <span class="text-danger">*</span></label>
                        <select name="role_slug" id="role_slug" class="form-control">
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
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Full Name<span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" placeholder="Enter Firm Name"
                            value="{{ isset($customer->full_name) ? $customer->full_name : old('full_name') }}"
                            class="">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label>Firm Name<span class="text-danger">*</span></label>
                        <input type="text" name="firm_name" class="form-control" placeholder="Enter Firm Name"
                            value="{{ isset($customer->firm_name) ? $customer->firm_name : old('firm_name') }}"
                            class="">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                {{-- End User Type & Firm Name --}}
                {{-- Email & Password --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Email<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" placeholder="Enter Email address"
                            value="{{ isset($customer->email) ? $customer->email : old('email') }}">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @if (!isset($customer))
                        <div class="form-group col-md-6">
                            <label>Password<span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" placeholder="Enter password"
                                value="{{ old('password') }}">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>
                {{-- End Email & Password --}}
                {{-- State & City & Pincode --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
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
                    <div class="form-group col-md-6">
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
                    <div class="form-group col-md-4">
                        <label for="html">Pincode<span class="text-danger">*</span></label>
                        <input type="number"
                            value="{{ isset($customer->pincode) ? $customer->pincode : old('pincode') }}"
                            class="form-control" name="pincode" placeholder="Enter Pincode">
                        @error('pincode')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                {{-- End State & City & Pincode --}}
                {{-- Contact-1 & Contact-2 --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="html">1. Contact Number<span class="text-danger">*</span></label>
                        <input type="number" class="form-control"
                            value="{{ isset($customer->contact_no_1) ? $customer->contact_no_1 : old('contact_no_1') }}"
                            name="contact_no_1" placeholder="Enter number">
                        @error('contact_no_1')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="html">2. Contact Number<span class="text-danger">*</span></label>
                        <input type="number" class="form-control"
                            value="{{ isset($customer->contact_no_2) ? $customer->contact_no_2 : old('contact_no_2') }}"
                            name="contact_no_2" placeholder="Enter number">
                        @error('contact_no_2')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                {{-- End Contact-1 & Contact-2 --}}
                {{-- GST-NO & DRUG-LIC-NO --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="html">GST No.<span class="text-danger">*</span></label>
                        <input type="text" class="form-control"
                            value="{{ isset($customer->gst_no) ? $customer->gst_no : old('gst_no') }}" name="gst_no"
                            placeholder="Enter GST No">
                        @error('gst_no')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="html">Drug Licence No.<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="drug_licence_no"
                            value="{{ isset($customer->drug_licence_no) ? $customer->drug_licence_no : old('drug_licence_no') }}"
                            placeholder="Enter Drug Licence No">
                        @error('drug_licence_no')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                {{-- End GST-NO & DRUG-LIC-NO --}}
                {{-- Doc Upload --}}
                <div class="form-row">
                    <div class="form-group col-md-6 in_mr">
                        <label for="html">Designation<span class="text-danger">*</span></label>
                        <input type="text"
                            value="{{ isset($customer->designation) ? $customer->designation : old('designation') }}"
                            class="form-control" name="designation" placeholder="Enter Designation">
                        @error('designation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 not_in_mr">
                        <label for="html">Upload GST Certificate<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="gst_document" value="{{ old('gst_document') }}">
                        @error('gst_document')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 not_in_mr">
                        <label for="html">Upload Drug Certificate<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="drug_document"
                            value="{{ old('drug_document') }}">
                        @error('drug_document')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="html">Upload Document ( ID Proof )<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="id_proof_document"
                            value="{{ old('id_proof_document') }}">
                        @error('id_proof_document')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                {{-- End Doc Upload --}}
                <div class="form-group mb-3">
                    @if (isset($customer))
                        <button class="btn btn-success" type="submit">Update</button>
                    @else
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button class="btn btn-success" type="submit">Submit</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            hideShowMr();
        });
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
                        });
                    }
                }
            });
        }
    </script>
@endpush
