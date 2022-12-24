@extends('backend.layouts.master')
{{-- {{ dd($errors) }} --}}
@section('main-content')
    <div class="card">
        <h5 class="card-header">
            @if (isset($product))
                Edit Product
            @else
                Add Product
            @endif
        </h5>
        <div class="card-body">
            <form method="post" action="{{ route('product.update', isset($product->id) ? $product->id : null) }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($product))
                    @method('PATCH')
                @endif
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Enter title"
                        value="{{ isset($product->title) ? $product->title : old('title') }}" class="form-control">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="summary"
                        name="summary">{{ isset($product->summary) ? $product->summary : old('summary') }}</textarea>
                    @error('summary')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="col-form-label">Description</label>
                    <textarea class="form-control" id="description"
                        name="description">{{ isset($product->description) ? $product->description : old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group d-none">
                    <label for="is_featured">Is Featured</label><br>
                    <input type="checkbox" name='is_featured' id='is_featured' value='1'
                        {{ isset($product->is_featured) && $product->is_featured ? 'checked' : old('is_featured') }}> Yes
                </div>
                {{-- {{$categories}} --}}

                <div class="form-group">
                    <label for="cat_id">Category <span class="text-danger">*</span></label>
                    <select name="cat_id" id="cat_id" class="form-control">
                        <option value="">--Select any category--</option>
                        @foreach ($categories as $key => $cat_data)
                            <option value='{{ $cat_data->id }}'
                                {{ isset($product->cat_id) && $product->cat_id == $cat_data->id ? 'selected' : '' }}>
                                {{ $cat_data->title }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- {{$product->child_cat_id}} --}}
                <div class="form-group d-none {{ isset($product->child_cat_id) && $product->child_cat_id ? '' : 'd-none' }}"
                    id="child_cat_div_change">
                    <label for="child_cat_id">Sub Category</label>
                    <select name="child_cat_id" id="child_cat_id" class="form-control">
                        <option value="">--Select any sub category--</option>

                    </select>
                </div>

                <div class="form-group">
                    <label for="price" class="col-form-label">Price(NRS) <span class="text-danger">*</span></label>
                    <input id="price" type="number" name="price" placeholder="Enter price"
                        value="{{ isset($product->price) ? $product->price : old('price') }}" class="form-control" min="0" oninput="this.value = Math.abs(this.value)">
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group d-none">
                    <label for="discount" class="col-form-label">Discount(%)</label>
                    <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Enter discount"
                        value="{{ isset($product->discount) ? $product->discount : old('discount') }}"
                        class="form-control">
                    @error('discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group d-none">
                    <label for="size">Size</label>
                    <select name="size[]" class="form-control selectpicker" multiple data-live-search="true">
                        <option value="">--Select any size--</option>
                        @if (isset($items) && !empty($items))
                            @foreach ($items as $item)
                                @php
                                    $data = explode(',', $item->size);
                                    // dd($data);
                                @endphp
                                <option value="S" @if (in_array('S', $data)) selected @endif>Small</option>
                                <option value="M" @if (in_array('M', $data)) selected @endif>Medium</option>
                                <option value="L" @if (in_array('L', $data)) selected @endif>Large</option>
                                <option value="XL" @if (in_array('XL', $data)) selected @endif>Extra Large</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group d-none">
                    <label for="brand_id">Brand</label>
                    <select name="brand_id" class="form-control">
                        <option value="">--Select Brand--</option>
                        @if (isset($brands))
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ isset($product->brand_id) && $product->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->title }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group d-none">
                    <label for="condition">Condition</label>
                    <select name="condition" class="form-control">
                        <option value="">--Select Condition--</option>
                        <option value="default"
                            {{ isset($product->condition) && $product->condition == 'default' ? 'selected' : '' }}>
                            Default</option>
                        <option value="new"
                            {{ isset($product->condition) && $product->condition == 'new' ? 'selected' : '' }}>New
                        </option>
                        <option value="hot"
                            {{ isset($product->condition) && $product->condition == 'hot' ? 'selected' : '' }}>Hot
                        </option>
                    </select>
                </div>

                <div class="form-group d-none">
                    <label for="stock">Quantity <span class="text-danger">*</span></label>
                    <input id="quantity" type="number" name="stock" min="0" placeholder="Enter quantity"
                        value="{{ isset($product->stock) ? $product->stock : old('stock') }}" class="form-control">
                    @error('stock')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
                    <input id="thumbnail" class="form-control" type="file" name="photo[]" multiple>

                    <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                    @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror                    
                    <div id="image_preview" class="row">
                        @if(isset($product->photo))
                        @foreach($product->photo as $key => $image)
                        <span class="pip">
                            <img class="imageThumb" src="{{ $image }}" title="{{ $image }}"/><br/>
                            <span class="remove">Remove image</span>
                            <input type='hidden' class='filter_image' name='previous_image[]' value="{{ $image }}">
                        </span>
                        @endforeach
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active"
                            {{ isset($product->status) && $product->status == 'active' ? 'selected' : '' }}>
                            Active</option>
                        <option value="inactive"
                            {{ isset($product->status) && $product->status == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    @if (isset($product))
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
                            $("<span class=\"pip\">" +
                                "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                                "<br/><span class=\"remove\">Remove image</span>" +
                                "<input type='hidden' class='filter_image' name='filter_image[]' value='" + e.target.result + "'></span>").insertAfter("#thumbnail");
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
            $('#description').summernote({
                placeholder: "Write detail Description.....",
                tabsize: 2,
                height: 150
            });
        });
    </script>

    <script>
        var child_cat_id = '{{ isset($product) ? $product->child_cat_id : '' }}';
        // alert(child_cat_id);
        $('#cat_id').change(function() {
            var cat_id = $(this).val();

            if (cat_id != null) {
                // ajax call
                $.ajax({
                    url: "/admin/category/" + cat_id + "/child",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (typeof(response) != 'object') {
                            response = $.parseJSON(response);
                        }
                        var html_option = "<option value=''>--Select any one--</option>";
                        if (response.status) {
                            var data = response.data;
                            if (response.data) {
                                $('#child_cat_div').removeClass('d-none');
                                $.each(data, function(id, title) {
                                    html_option += "<option value='" + id + "' " + (
                                            child_cat_id == id ? 'selected ' : '') + ">" +
                                        title + "</option>";
                                });
                            } else {
                                console.log('no response data');
                            }
                        } else {
                            $('#child_cat_div').addClass('d-none');
                        }
                        $('#child_cat_id').html(html_option);

                    }
                });
            } else {

            }

        });
        if (child_cat_id != null) {
            $('#cat_id').change();
        }
    </script>
@endpush
