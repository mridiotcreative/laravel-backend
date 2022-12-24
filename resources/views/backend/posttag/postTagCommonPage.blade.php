@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">
            @if (isset($postTag))
                Edit Post Tag
            @else
                Add Post Category
            @endif
        </h5>
        <div class="card-body">
            <form method="post" action="{{ route('post-tag.update', isset($postTag->id) ? $postTag->id : null) }}">
                @csrf
                @if (isset($postTag))
                    @method('PATCH')
                @endif
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Title</label>
                    <input id="inputTitle" type="text" name="title" placeholder="Enter title"
                        value="{{ isset($postTag->title) ? $postTag->title : old('title') }}" class="form-control">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="col-form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active"
                            {{ isset($postTag->status) && $postTag->status == 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="inactive"
                            {{ isset($postTag->status) && $postTag->status == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    @if (isset($postTag))
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
