@extends('admin.layouts.app')

@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">

        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Vendor</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Vendor</a></li>
                            <li class="breadcrumb-item"><a href="#">Edit Vendor</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Vendor Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="username" value="{{ old('username', $vendor->name) }}" placeholder="Name" required>
                                        @error('username')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email',$vendor->email) }}" placeholder="Email" required>
                                        @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <div id="quill-editor" style="height: 200px;">{!! old('description', $vendor->description) !!}</div>
                                        <textarea name="description" id="description" class="d-none">{{ old('description', $vendor->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4 order-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>Settings</h5>
                        </div>
                        <div class="card-body">

                            <!-- Status -->
                            <div class="col-md-12 p-0">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control select-user">
                                        <option value="{{ config('constants.status.active') }}" {{ $vendor->status == config('constants.status.active') ? 'selected' : '' }}>Active</option>
                                        <option value="{{ config('constants.status.inactive') }}" {{ $vendor->status == config('constants.status.inactive') ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="form-group">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="thumbnail" id="avatarInput" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        <label class="custom-file-label overflow-hidden" for="avatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported image types: JPG, JPEG, PNG, or GIF.</small>
                                @error('thumbnail')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                {{-- Avatar Preview --}}
                                <div id="avatar-preview-container" class="row mt-3 {{ !empty($vendor->thumbnail) ? '' : 'd-none' }}">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="avatar-preview"
                                                src="{{ !empty($vendor->thumbnail) ? asset('storage/' . $vendor->thumbnail) : asset('assets/images/no-image-available.png') }}"
                                                class="card-img-top img-thumbnail"
                                                alt="Avatar Preview"
                                                style="object-fit: cover; height: 120px; width: 100%;">
                                            <button type="button"
                                                id="remove-avatar-preview"
                                                class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                                title="Remove avatar">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">
                            </div>

                            <!-- Submit Button -->
                            <div class="text-right mt-0">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- [ Main Content ] end -->
    </div>
</section>
@endsection