@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5>Add Email</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="#!">Email</a></li>
                            <li class="breadcrumb-item"><a href="#!">Add Email</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('emails.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Email Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Title<span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            name="title"
                                            id="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            placeholder="Enter Email Template Name"
                                            value="{{ old('title') }}">
                                        @error('title')
                                        <span class="invalid-feedback d-block" id="titleNameError" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Slug<span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            name="slug"
                                            id="slug"
                                            class="form-control @error('slug') is-invalid @enderror"
                                            placeholder="Enter Slug"
                                            value="{{ old('slug') }}">
                                        @error('slug')
                                        <span class="invalid-feedback d-block" id="slugNameError" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Subject<span class="text-danger"></span></label>
                                        <input
                                            type="text"
                                            name="subject"
                                            id="subject"
                                            class="form-control  @error('subject') is-invalid @enderror"
                                            placeholder="Enter Subject"
                                            value="{{ old('subject') }}">
                                        @error('subject')
                                        <span class="invalid-feedback d-block" id="macroNameError" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Dummy Template<span class="text-danger"></span></label>
                                        <input
                                            type="text"
                                            name="dummy_template"
                                            id="dummy_template"
                                            class="form-control"
                                            placeholder="Enter Dummy Template"
                                            value="{{ old('dummy_template') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Macro<span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            name="macro"
                                            id="macro"
                                            class="form-control @error('macro') is-invalid @enderror"
                                            placeholder="Enter Macro"
                                            value="{{ old('macro') }}">
                                        @error('macro')
                                        <span class="invalid-feedback d-block" id="macroNameError" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email Content</label>
                                        <div id="quill-editor" style="height: 200px;">{!! old('email_content') !!}</div>
                                        <textarea name="email_content"
                                            id="email_content"
                                            class="d-none @error('email_content') is-invalid @enderror">{{ old('email_content') }}</textarea>
                                        @error('email_content')
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
                                            {{ old('status', 1) == config('constants.status.active') ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="{{ config('constants.status.inactive') }}"
                                            {{ old('status', 1) == config('constants.status.inactive') ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
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
<script>
    document.getElementById('slug').addEventListener('input', function() {
        let val = this.value;
        // force lowercase + replace spaces with underscores
        this.value = val.toLowerCase().replace(/\s+/g, '_');
    });
</script>
@endsection