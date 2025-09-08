@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5>Edit Email</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('emails.list') }}">Email</a></li>
                            <li class="breadcrumb-item"><a href="#!">Edit Email</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('emails.update', $getEmailId->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Email Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Title -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Title<span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            name="title"
                                            id="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            value="{{ old('title', $getEmailId->title) }}"
                                            placeholder="Enter Email Template Name">
                                        @error('title')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Slug -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Slug<span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            name="slug"
                                            id="slug"
                                            class="form-control @error('slug') is-invalid @enderror"
                                            value="{{ old('slug', $getEmailId->slug) }}"
                                            placeholder="Enter Slug">
                                        @error('slug')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Subject -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Subject</label>
                                        <input
                                            type="text"
                                            name="subject"
                                            id="subject"
                                            class="form-control"
                                            value="{{ old('subject', $getEmailId->subject) }}"
                                            placeholder="Enter Subject">
                                    </div>
                                </div>

                                <!-- Dummy Template -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Dummy Template</label>
                                        <input
                                            type="text"
                                            name="dummy_template"
                                            id="dummy_template"
                                            class="form-control"
                                            value="{{ old('dummy_template', $getEmailId->dummy_template) }}"
                                            placeholder="Enter Dummy Template">
                                    </div>
                                </div>

                                <!-- Macro -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Macro<span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            name="macro"
                                            id="macro"
                                            class="form-control @error('macro') is-invalid @enderror"
                                            value="{{ old('macro', $getEmailId->macro) }}"
                                            placeholder="Enter Macro">
                                        @error('macro')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email Content -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email Content <span class="text-danger">*</span></label>
                                        <div id="quill-editor"
                                            style="height: 200px;"
                                            class="@error('email_content') border border-danger @enderror">
                                            {!! old('email_content', $getEmailId->email_content) !!}
                                        </div>
                                        <textarea name="email_content" id="email_content" class="d-none">
                                            {{ old('email_content', $getEmailId->email_content) }}
                                        </textarea>
                                        @error('email_content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
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
                            <div class="form-group col-md-12 p-0">
                                <!-- Status -->
                                <div class="form-group mt-3">
                                    <label for="status" class="form-label d-block">Status</label>
                                    <select name="status" id="status" class="form-control select-user">
                                        <option value="{{ config('constants.status.active') }}"
                                            {{ old('status', $getEmailId->status) == config('constants.status.active') ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="{{ config('constants.status.inactive') }}"
                                            {{ old('status', $getEmailId->status) == config('constants.status.inactive') ? 'selected' : '' }}>
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
                                    <button type="submit" class="btn btn-primary">Update</button>
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
