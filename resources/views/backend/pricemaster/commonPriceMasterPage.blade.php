@extends('backend.layouts.master')
@section('title', 'E-SHOP || Price Master Edit')
@section('main-content')

    <div class="card">
        <h5 class="card-header">
            @if (isset($pricemaster))
                Edit Price Master
            @else
                Add Price Master
            @endif
        </h5>
        <div class="card-body">
            <form method="post" action="{{ route('pricemaster.update', isset($pricemaster->id) ? $pricemaster->id : null) }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($pricemaster))
                    @method('PATCH')
                @endif

                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Product Name <span class="text-danger">*</span></label>
                    <select name="product_id" class="form-control">
                        <option value="">-- Select Any Product --</option>
                        @foreach($productdata as $key => $product)
                            <option value="{{ $product->id }}" {{ isset($pricemaster->product_id) && $pricemaster->product_id == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">User Name <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-control">
                        <option value="">-- Select Any User --</option>
                        @foreach($customerdata as $cskey => $customer)
                            <option value="{{ $customer->id }}" {{ isset($pricemaster->user_id) && $pricemaster->user_id == $customer->id ? 'selected' : '' }}> {{ $customer->full_name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputDesc" class="col-form-label">Special Price</label>
                    <input id="inputTitle" type="number" name="special_price" min="0" oninput="this.value = Math.abs(this.value)" placeholder="Special Price"
                        value="{{ isset($pricemaster->special_price) ? $pricemaster->special_price : old('special_price') }}" class="form-control">
                    @error('special_price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active"
                            {{ isset($pricemaster->status) && $pricemaster->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive"
                            {{ isset($pricemaster->status) && $pricemaster->status == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    @if (isset($pricemaster))
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
