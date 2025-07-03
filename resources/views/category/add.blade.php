@extends('layouts.app')

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

        <form action="{{ route('category.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5>Category Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
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


                            <div class="custom-control custom-checkbox mt-2">
                                <input type="checkbox" name="status" value="1" class="custom-control-input" id="status" checked>
                                <label class="custom-control-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-left">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('category.list') }}" class="btn btn-secondary ml-2">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection