@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5>Edit Category</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('category.list') }}">Categories</a>
                            </li>
                            <li class="breadcrumb-item">Edit Category</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Category Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category_name">Category Name</label>
                                        <input type="text"
                                            name="category_name"
                                            id="category_name"
                                            class="form-control @error('category_name') is-invalid @enderror"
                                            placeholder="Enter category name"
                                            value="{{ old('category_name', $category->category_name) }}"
                                            required>
                                        @error('category_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Original Avatar Upload -->
                <div class="col-md-4 order-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group col-md-12 p-0">
                                <!-- Status -->
                                <div class="form-group mt-3">
                                    <label for="status" class="form-label d-block">Status</label>
                                    <select name="status" id="status" class="form-control select-user">
                                        <option value="{{ config('constants.status.active') }}"
                                            {{ old('status', $category->status) == config('constants.status.active') ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="{{ config('constants.status.inactive') }}"
                                            {{ old('status', $category->status) == config('constants.status.inactive') ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>

                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Styled Preview Like Thumbnail --}}
                                <label class="form-label">Featured Image</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="thumbnail" id="thumbnail"
                                            accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        <label class="custom-file-label overflow-hidden" for="thumbnail">Choose file...</label>
                                    </div>
                                </div>

                                <small class="form-text text-muted">
                                    Supported image types: JPG, JPEG, PNG, or GIF.
                                </small>
                                @error('thumbnail')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror

                                <div class="col-md-12 mt-4 {{ !empty($category->thumbnail) ? '' : 'd-none'}}" id="preview-container">
                                    <div class="form-group">
                                        <div class="mb-2 position-relative d-inline-block">
                                            <img src="{{ !empty($category->thumbnail) ? asset('storage/' . $category->thumbnail) : asset('assets/images/no-image-available.png') }}"
                                                alt="Category thumbnail"
                                                id="preview-image"
                                                class="rounded shadow-sm"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                            <button type="button"
                                                id="remove-preview"
                                                class="btn btn-sm btn-dark text-white rounded-pill delete-existing-image position-absolute top-0 end-0"
                                                title="Remove image">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_existing_thumbnail" id="remove_existing_thumbnail" value="0">
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection