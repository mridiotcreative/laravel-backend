@extends('backend.layouts.master')
@section('title', 'E-SHOP || CMS Edit')
@section('main-content')

    <div class="card">
        <h5 class="card-header">
            @if (isset($cms))
                Edit CMS
            @else
                Add CMS
            @endif
        </h5>
        <div class="card-body">
            <form method="post" action="{{ route('cms.update', isset($cms->id) ? $cms->id : null) }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($cms))
                    @method('PATCH')
                @endif

                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Slug <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="slug" placeholder="Enter slug"
                        value="{{ isset($cms->slug) ? $cms->slug : old('slug') }}" {{ isset($cms->slug) ? 'readonly' : '' }} class="form-control">
                    @error('slug')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Enter title"
                        value="{{ isset($cms->title) ? $cms->title : old('title') }}" class="form-control">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputDesc" class="col-form-label">Description</label>
                    <textarea class="form-control" id="description" name="description">
                        {{ isset($cms->description) ? $cms->description : old('description') }}
                    </textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active"
                            {{ isset($cms->status) && $cms->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive"
                            {{ isset($cms->status) && $cms->status == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    @if (isset($cms))
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
