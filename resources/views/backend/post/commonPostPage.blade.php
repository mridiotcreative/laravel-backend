@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">
            @if (isset($post))
                Edit Post
            @else
                Add Post
            @endif
        </h5>
        <div class="card-body">
            <form method="post" action="{{ route('post.update', isset($post->id) ? $post->id : null) }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($post))
                    @method('PATCH')
                @endif
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Enter title"
                        value="{{ isset($post->title) ? $post->title : old('title') }}" class="form-control">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- <div class="form-group">
                    <label for="quote" class="col-form-label">Quote</label>
                    <textarea class="form-control" id="quote"
                        name="quote">{{ isset($post->quote) ? $post->quote : old('quote') }}</textarea>
                    @error('quote')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}

                <div class="form-group">
                    <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="summary"
                        name="summary">{{ isset($post->summary) ? $post->summary : old('summary') }}</textarea>
                    @error('summary')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="col-form-label">Description</label>
                    <textarea class="form-control" id="description"
                        name="description">{{ isset($post->description) ? $post->description : old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="post_cat_id">Category <span class="text-danger">*</span></label>
                    <select name="post_cat_id" class="form-control">
                        <option value="">--Select any category--</option>
                        @foreach ($categories as $key => $data)
                            <option value='{{ $data->id }}'
                                {{ isset($post->post_cat_id) && $data->id == $post->post_cat_id ? 'selected' : '' }}>
                                {{ $data->title }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- {{$post->tags}} --}}
                @php
                    $post_tags = isset($post->tags) ? explode(',', $post->tags) : [];
                    // dd($tags);
                @endphp
                <div class="form-group">
                    <label for="tags">Tag</label>
                    <select name="tags[]" multiple data-live-search="true" class="form-control selectpicker">
                        <option value="">--Select any tag--</option>
                        @foreach ($tags as $key => $data)
                            <option value="{{ $data->title }}"
                                {{ in_array("$data->title", $post_tags) ? 'selected' : '' }}>{{ $data->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="form-group">
                    <label for="added_by">Author</label>
                    <select name="added_by" class="form-control">
                        <option value="">--Select any one--</option>
                        @foreach ($users as $key => $data)
                            <option value='{{ $data->id }}'
                                {{ isset($post->added_by) && $post->added_by == $data->id ? 'selected' : '' }}>
                                {{ $data->name }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
                    <input id="thumbnail" class="form-control" type="file" name="photo">
                    <div id="holder" style="margin-top:15px;max-height:100px;"></div>

                    @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div id="image_preview" class="row">
                        @if(isset($post->photo))
                        <span class="pip">
                            <img class="imageThumb" src="{{ $post->photo[0] }}" title="{{ $post->photo[0] }}"/><br/>
                            <span class="remove">Remove image</span>
                            <input type='hidden' class='filter_image' name='previous_image' value="{{ $post->photo[0] }}">
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active" {{ isset($post->status) && $post->status == 'active' ? 'selected' : '' }}>
                            Active</option>
                        <option value="inactive"
                            {{ isset($post->status) && $post->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    @if (isset($post))
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

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

        $(document).ready(function() {
            $('#quote').summernote({
                placeholder: "Write short Quote.....",
                tabsize: 2,
                height: 100
            });
        });
        $(document).ready(function() {
            $('#description').summernote({
                placeholder: "Write detail description.....",
                tabsize: 2,
                height: 150
            });
        });
    </script>
@endpush
