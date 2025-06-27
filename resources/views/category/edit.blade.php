@extends('layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
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
                            <li class="breadcrumb-item"><a href="{{ route('category.list') }}">Categories</a></li>
                            <li class="breadcrumb-item">Edit Category</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('category.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header">
                    <h5>Category Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Category Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
                                <input type="text" name="category_name" id="category_name" class="form-control"
                                       placeholder="Enter category name"
                                       value="{{ old('category_name', $category->category_name) }}" required>
                            </div>

                            <!-- Status Checkbox -->
                            <div class="form-check mt-2">
                                {{-- Hidden input ensures a value is always submitted --}}
                                <input type="hidden" name="status" value="{{ config('constants.status.inactive') }}">
                                
                                <input type="checkbox" name="status" value="{{ config('constants.status.active') }}"
                                       class="form-check-input" id="status"
                                       {{ $category->status == config('constants.status.active') ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
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
