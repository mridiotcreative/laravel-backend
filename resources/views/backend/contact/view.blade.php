@extends('backend.layouts.master')

@section('main-content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('backend.layouts.notification')
            </div>
        </div>
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">Contact Us View</h6>
            <a href="{{ route('contact-us.index') }}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip"
                data-placement="bottom" title="Add User">Back</a>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group row">
                    <label for="staticEmail" class="col-12 col-form-label">Name: {{ $contactUS->name }}</label>
                </div>
                <div class="form-group row">
                    <label for="staticEmail" class="col-12 col-form-label">Eamil: {{ $contactUS->email }}</label>
                </div>
                <div class="form-group row">
                    <label for="staticEmail" class="col-12 col-form-label">Message: {{ $contactUS->message }}</label>
                </div>
            </form>
        </div>
    </div>
@endsection
