@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">
            @if (isset($coupon))
                Edit Coupon
            @else
                Add Coupon
            @endif
        </h5>
        <div class="card-body">
            <form method="post" action="{{ route('coupon.update', isset($coupon->id) ? $coupon->id : null) }}">
                @csrf
                @if (isset($coupon))
                    @method('PATCH')
                @endif
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Coupon Code <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="code" placeholder="Enter Coupon Code"
                        value="{{ isset($coupon->code) ? $coupon->code : old('code') }}" class="form-control">
                    @error('code')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type" class="col-form-label">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-control">
                        <option value="fixed" {{ isset($coupon->type) && $coupon->type == 'fixed' ? 'selected' : '' }}>
                            Fixed</option>
                        <option value="percent"
                            {{ isset($coupon->type) && $coupon->type == 'percent' ? 'selected' : '' }}>Percent</option>
                    </select>
                    @error('type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Value <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="number" name="value" placeholder="Enter Coupon value"
                        value="{{ isset($coupon->value) ? $coupon->value : old('value') }}" class="form-control">
                    @error('value')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active"
                            {{ isset($coupon->status) && $coupon->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive"
                            {{ isset($coupon->status) && $coupon->status == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    @if (isset($coupon))
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

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/summernote/summernote.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('backend/summernote/summernote.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#description').summernote({
                placeholder: "Write short description.....",
                tabsize: 2,
                height: 150
            });
        });
    </script>
@endpush
