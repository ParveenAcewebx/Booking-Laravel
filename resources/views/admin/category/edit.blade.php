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

            <div class="card">
                <div class="card-header">
                    <h5>Category Details</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Category Name & Status -->
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
                            <div class="custom-control custom-checkbox mt-2">
                                <input type="hidden" name="status" value="{{ config('constants.status.inactive') }}">
                                <input type="checkbox"
                                       name="status"
                                       value="{{ config('constants.status.active') }}"
                                       class="custom-control-input"
                                       id="status"
                                       {{ $category->status == config('constants.status.active') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">Active</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="thumbnail">Upload New Thumbnail</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('thumbnail') is-invalid @enderror"
                                               name="thumbnail"
                                               id="thumbnail">
                                        <label class="custom-file-label" for="thumbnail">Choose file</label>
                                    </div>
                                </div>
                                @error('thumbnail')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Thumbnail -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Current Thumbnail</label>
                                <div class="mb-2">
                                    <img src="{{ !empty($category->thumbnail) ? asset('storage/' . $category->thumbnail) : asset('assets/images/no-image-available.png') }}"
                                         alt="Category thumbnail"
                                         class="rounded shadow-sm"
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Submit -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-left">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('category.list') }}" class="btn btn-secondary ml-2">Back</a>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
