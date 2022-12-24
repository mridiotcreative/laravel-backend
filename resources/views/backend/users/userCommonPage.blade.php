@extends('backend.layouts.master')
{{-- {{ dd($errors) }} --}}
@section('main-content')
    <div class="card">
        <h5 class="card-header">
            @if (isset($user))
                Edit User
            @else
                Add User
            @endif
        </h5>
        <div class="card-body">
            <form method="post" action="{{ route('users.update', isset($user->id) ? $user->id : null) }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($user))
                    @method('PATCH')
                @endif
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Name</label>
                    <input id="inputTitle" type="text" name="name" placeholder="Enter name"
                        value="{{ isset($user->name) ? $user->name : old('name') }}" class="form-control">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputEmail" class="col-form-label">Email</label>
                    <input id="inputEmail" type="email" name="email" placeholder="Enter email"
                        value="{{ isset($user->email) ? $user->email : old('email') }}" class="form-control">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (!isset($user))
                    <div class="form-group">
                        <label for="inputPassword" class="col-form-label">Password</label>
                        <input id="inputPassword" type="password" name="password" placeholder="Enter password"
                            value="{{ old('password') }}" class="form-control">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photo</label>
                    <input id="thumbnail" class="form-control" type="file" name="photo">
                    <img id="holder" style="margin-top:15px;max-height:100px;">
                    @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div id="image_preview" class="row">
                        @if(isset($user->photo))
                        <span class="pip">
                            <img class="imageThumb" src="{{ (new \App\Helpers\AppHelper)->getStorageUrl(config('path.user'), $user->photo) }}" title="{{ $user->photo }}"/><br/>
                            <span class="remove">Remove image</span>
                            <input type='hidden' class='filter_image' name='previous_image' value="{{ $user->photo }}">
                        </span>
                        @endif
                    </div>
                </div>
                @php
                    $rolesQuery = DB::table('users')->select('role');
                    if (isset($user->id)) {
                        $rolesQuery->where('id', $user->id);
                    }
                    $roles = $rolesQuery->get();
                    // dd($roles);
                @endphp
                <div class="form-group">
                    <label for="role" class="col-form-label">Role</label>
                    <select name="role" class="form-control">
                        <option value="">-----Select Role-----</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->role }}" {{ $role->role == 'admin' ? 'selected' : '' }}>Admin
                            </option>
                            <option value="{{ $role->role }}" {{ $role->role == 'user' ? 'selected' : '' }}>User
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status" class="col-form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ isset($user->status) && $user->status == 'active' ? 'selected' : '' }}>
                            Active</option>
                        <option value="inactive"
                            {{ isset($user->status) && $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    @if (isset($user))
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
        });
    </script>
@endpush
