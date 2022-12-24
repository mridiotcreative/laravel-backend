@extends('backend.layouts.master')
@section('title', 'E-SHOP || Banner Edit')
@section('main-content')

    <div class="card">
        <h5 class="card-header">
            @if (isset($banner))
                Edit Banner
            @else
                Add Banner
            @endif
        </h5>
        <div class="card-body">
            <form method="post" action="{{ route('banner.update', isset($banner->id) ? $banner->id : null) }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($banner))
                    @method('PATCH')
                @endif
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Enter title"
                        value="{{ isset($banner->title) ? $banner->title : old('title') }}" class="form-control">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputDesc" class="col-form-label">Description</label>
                    <textarea class="form-control" id="description" name="description">
                        {{ isset($banner->description) ? $banner->description : old('description') }}
                    </textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
                    <input id="thumbnail" class="form-control" type="file" name="photo"
                        value="{{ isset($banner->photo) ? $banner->photo : old('photo') }}">

                    <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                    @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div id="image_preview" class="row">
                        @if(isset($banner->photo))
                        <span class="pip">
                            <img class="imageThumb" src="{{ (new \App\Helpers\AppHelper)->getStorageUrl(config('path.banner'), $banner->photo) }}" title="{{ $banner->photo }}"/><br/>
                            <span class="remove">Remove image</span>
                            <input type='hidden' class='filter_image' name='previous_image' value="{{ $banner->photo }}">
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active"
                            {{ isset($banner->status) && $banner->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive"
                            {{ isset($banner->status) && $banner->status == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    @if (isset($banner))
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

            $('#description').summernote({
                placeholder: "Write short description.....",
                tabsize: 2,
                height: 150
            });
        });
    </script>
@endpush
