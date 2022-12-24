@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">Edit Settings</h5>
        <div class="card-body">
            @if(count($data) > 0)
                <form method="post" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    {{-- @method('PATCH') --}}
                    {{-- {{ dd($data) }} --}}

                    @foreach($data as $key => $value)
                        <div class="form-group">
                            <label for="short_des" class="col-form-label">{{ str_replace('_', ' ', $value->keys_data) }} <span
                                    class="text-danger">*</span></label>
                                <input type="hidden" name="keys_data[]" value="{{ str_replace(' ', '_', $value->keys_data) }}">
                                <input type="hidden" name="keys_data_id[{{ str_replace(' ', '_', $value->keys_data) }}]" value="{{ $value->id }}">
                            @if($value->type == 1)
                                <textarea class="form-control summernoteDiv" id="summernote" required name="values_data[{{ str_replace(' ', '_', $value->keys_data) }}]">{{ $value->values_data }}</textarea>
                            @elseif($value->type == 2)
                                <input id="thumbnail1" class="thumbnail form-control" type="file" name="values_data[{{ str_replace(' ', '_', $value->keys_data) }}]">
                                <div id="image_preview" class="row">
                                    @if(isset($value->values_data))
                                    <span class="pip">
                                        <img class="imageThumb" src="{{ (new \App\Helpers\AppHelper)->getStorageUrl(config('path.site_logo'), $value->values_data) }}" title="{{ $value->values_data }}"/><br/>
                                        <span class="remove">Remove image</span>
                                        <input type='hidden' class='filter_image' name='previous_image' value="{{ $value->values_data }}">
                                    </span>
                                    @endif
                                </div>
                            @elseif($value->type == 3)
                                <input type="text" class="form-control" name="values_data[{{ str_replace(' ', '_', $value->keys_data) }}]" required value="{{ $value->values_data }}">
                            @endif
                            @error('keys_data')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="form-group mb-3">
                        <button class="btn btn-success" type="submit">Update</button>
                    </div>
                </form>
            @else
                <div class="form-group mb-3">No Data Found</div>
            @endif
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
                $(".thumbnail").on("change", function(e) {
                    var mainDiv = this;
                    var files = e.target.files,
                    filesLength = files.length;
                    for (var i = 0; i < filesLength; i++) {
                        var f = files[i]
                        var fileReader = new FileReader();
                        fileReader.onload = (function(e) {
                            var file = e.target;
                            //console.log(file);
                            $(mainDiv).next("#image_preview").html("");
                            $(mainDiv).next("#image_preview").html("<span class=\"pip\">" +
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

            $(".summernoteDiv").each(function(){
                $(this).summernote({
                    placeholder: "Write short description.....",
                    tabsize: 2,
                    height: 150
                });
            });

            // $('#summary').summernote({
            //     placeholder: "Write short description.....",
            //     tabsize: 2,
            //     height: 150
            // });
        });

        // $(document).ready(function() {
        //     $('#quote').summernote({
        //         placeholder: "Write short Quote.....",
        //         tabsize: 2,
        //         height: 100
        //     });
        // });
        // $(document).ready(function() {
        //     $('#description').summernote({
        //         placeholder: "Write detail description.....",
        //         tabsize: 2,
        //         height: 150
        //     });
        // });
    </script>
@endpush
