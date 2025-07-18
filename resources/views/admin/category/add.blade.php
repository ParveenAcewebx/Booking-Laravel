@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5>Add Category</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('category.list') }}">Categories</a></li>
                            <li class="breadcrumb-item">Add Category</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Category Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Category Name</label>
                                        <input
                                            type="text"
                                            name="category_name"
                                            id="category_name"
                                            class="form-control @error('category_name') is-invalid @enderror"
                                            placeholder="Enter category name"
                                            value="{{ old('category_name') }}"
                                            required>
                                        @error('category_name')
                                        <span class="invalid-feedback d-block" id="categoryNameError" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
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
                                            {{ old('status', config('constants.status.active')) == config('constants.status.active') ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="{{ config('constants.status.inactive') }}"
                                            {{ old('status') == config('constants.status.inactive') ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                    <div class="text-danger">{{ $message }}</div>
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
                                <div id="image-preview-container" class="row d-none mt-3">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="image-preview" class="card-img-top img-thumbnail" alt="Image Preview">

                                            {{-- Gallery-style close button --}}
                                            <button type="button"
                                                id="remove-preview"
                                                class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                                title="Remove image">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @error('thumbnail')
                                <div class="error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection