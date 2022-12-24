@extends('backend.layouts.master')

@section('title','Customer Detail')

@section('main-content')
<div class="card">
<h5 class="card-header">Customer</h5>
  <div class="card-body">
    @if($customer)

    <section class="confirmation_part section_padding">
      <div class="customer_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="customer-info">
              <h4 class="text-center pb-4">customer INFORMATION</h4>
              <table class="table">
                    <tr class="">
                        <td>User Type</td>
                        <input type="hidden" id="role_slug" value="{{ !empty($customer->roles) ? $customer->roles[0]->slug : '' }}">
                        <td> : {{ !empty($customer->roles) ? $customer->roles[0]->name : '' }} </td>
                    </tr>
                    <tr>
                        <td>Full Name</td>
                        <td> : {{$customer->full_name }} </td>
                    </tr>
                    <tr>
                        <td>Firm Name</td>
                        <td> : {{$customer->firm_name}}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td> : {{$customer->email}}</td>
                    </tr>
                    <tr>
                        <td>State</td>
                        <td> : {{ !empty($customer->state) ? $customer->state->name : '' }}</td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td> : {{ !empty($customer->city) ? $customer->city->name : '' }} </td>
                    </tr>
                    <tr>
                      <td>Pincode</td>
                      <td> : {{ isset($customer->pincode) ? $customer->pincode : old('pincode') }}</td>
                    </tr>
                    <tr>
                        <td>1. Contact Number</td>
                        <td> : {{ isset($customer->contact_no_1) ? $customer->contact_no_1 : old('contact_no_1') }}</td>
                    </tr>
                    <tr>
                        <td>2. Contact Number</td>
                        <td> : {{ isset($customer->contact_no_2) ? $customer->contact_no_2 : old('contact_no_2') }}</td>
                    </tr>
                    <tr>
                        <td>GST No.</td>
                        <td> : {{ isset($customer->gst_no) ? $customer->gst_no : old('gst_no') }}</td>
                    </tr>
                    <tr>
                        <td>Drug Licence No.</td>
                        <td> : {{ isset($customer->drug_licence_no) ? $customer->drug_licence_no : old('drug_licence_no') }}</td>
                    </tr>
                    <tr class="in_mr">
                        <td>Designation</td>
                        <td> : {{ isset($customer->designation) ? $customer->designation : old('designation') }}</td>
                    </tr>
                    <tr class="not_in_mr">
                        <td>GST Certificate</td>
                        <td> : <a class="btn btn-success btn-sm mr-1"
                        href="{{ $customer->getGstDocument() }}" target="_blank"
                        data-toggle="tooltip" title="GST Document"><i
                            class="fas fa-file-pdf"></i></a> </td>
                    </tr>
                    <tr class="not_in_mr">
                        <td>Drug Certificate</td>
                        <td> : <a class="btn btn-success btn-sm mr-1"
                            href="'{{ $customer->getDrugDocument() }}" target="_blank"
                            data-toggle="tooltip" title="Grug Document"><i
                                class="fas fa-file-pdf"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Document ( ID Proof )</td>
                        <td> : <a class="btn btn-success btn-sm mr-1"
                        href="{{ $customer->getIdProofDocument() }}" target="_blank"
                        data-toggle="tooltip" title="Id Proof Document"><i
                            class="fas fa-file-pdf"></i></a>
                        </td>
                    </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

  </div>
</div>
@endsection

@push('styles')
<style>
    .customer-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .customer-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            hideShowMr();
        });

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
    </script>
@endpush

