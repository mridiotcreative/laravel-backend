@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">
            @if (isset($category))
                Edit Category
            @else
                Add Category
            @endif
        </h5>
        <div class="card-body">
            <form method="post" action="{{ route('category.update', isset($category->id) ? $category->id : null) }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($category))
                    @method('PATCH')
                @endif
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Enter title"
                        value="{{ isset($category->title) ? $category->title : old('title') }}" class="form-control">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="summary" class="col-form-label">Summary</label>
                    <textarea class="form-control" id="summary"
                        name="summary">{{ isset($category->summary) ? $category->summary : old('summary') }}</textarea>
                    @error('summary')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="is_featured">Is Featured</label><br>
                    <input type="checkbox" name='is_featured' id='is_featured' value='1'
                        {{ isset($category->is_featured) && $category->is_featured == 1 ? 'checked' : old('is_featured') }}> Yes
                </div>

                <div class="form-group d-none">
                    <label for="is_parent">Is Parent</label><br>
                    <input type="checkbox" name='is_parent' id='is_parent' value='1'
                        {{ isset($category->is_parent) && $category->is_parent == 1 ? 'checked' : old('is_parent') }}> Yes
                </div>
                {{-- {{$parent_cats}} --}}
                {{-- {{$category}} --}}

                <div class="form-group d-none {{ isset($category->is_parent) && $category->is_parent == 1 ? 'd-none' : '' }}"
                    id='parent_cat_div'>
                    <label for="parent_id">Parent Category</label>
                    <select name="parent_id" class="form-control">
                        <option value="">--Select any category--</option>
                        @foreach ($parent_cats as $key => $parent_cat)
                            <option value='{{ $parent_cat->id }}'
                                {{ isset($category->parent_id) && $parent_cat->id == $category->parent_id ? 'selected' : '' }}>
                                {{ $parent_cat->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photo</label>
                    <input id="thumbnail" class="form-control" type="file" name="photo">
                    <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                    @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div id="image_preview" class="row">
                        @if(isset($category->photo))
                        <span class="pip">
                            <img class="imageThumb" src="{{ $category->photo }}" title="{{ $category->photo }}"/><br/>
                            <span class="remove">Remove image</span>
                            <input type='hidden' class='filter_image' name='previous_image' value="{{ basename($category->photo) }}">
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active"
                            {{ isset($category->status) && $category->status == 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="inactive"
                            {{ isset($category->status) && $category->status == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    @if (isset($category))
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
    <style>
        .imageThumb {
            max-height: 75px;
            border: 2px solid;
            padding: 1px;
            cursor: pointer;
            width: 107px;
            height: 99px;
        }
        .pip {
            display: inline-block;
            margin: 10px 10px 0 0;
        }
        .remove {
            display: block;
            background: #444;
            border: 1px solid black;
            color: white;
            text-align: center;
            cursor: pointer;
        }
        .remove:hover {
            background: white;
            color: black;
        }

    </style>
@endpush
@push('scripts')
    <script src="{{ asset('backend/summernote/summernote.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if (window.File && window.FileList && window.FileReader) {
                $("#thumbnail").on("change", function(e) {
                    var files = e.target.files,
                    filesLength = files.length;
                    for (var i = 0; i < filesLength; i++) {
                        var f = files[i]
                        var fileReader = new FileReader();
                        fileReader.onload = (function(e) {
                            var file = e.target;
                            //console.log(file);
                            $("#image_preview").html("");
                            $("#image_preview").html("<span class=\"pip\">" +
                                "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                                "<br/><span class=\"remove\">Remove image</span>" +
                                "<input type='hidden' class='filter_image' name='filter_image' value='" + e.target.result + "'></span>");
                        });
                        fileReader.readAsDataURL(f);
                    }
                });
            } else {
                //alert("Your browser doesn't support to File API")
            }

            $(document).on("click", ".remove", function() {
                $(this).parent(".pip").remove();
            });
            $('#summary').summernote({
                placeholder: "Write short description.....",
                tabsize: 2,
                height: 150
            });
        });
    </script>
    <script>
        $('#is_parent').change(function() {
            var is_checked = $('#is_parent').prop('checked');
            // alert(is_checked);
            if (is_checked) {
                $('#parent_cat_div').addClass('d-none');
                $('#parent_cat_div').val('');
            } else {
                $('#parent_cat_div').removeClass('d-none');
            }
        })
    </script>
@endpush
