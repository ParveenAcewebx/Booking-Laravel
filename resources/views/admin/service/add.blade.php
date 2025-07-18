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
                            <h5 class="m-b-10">Add Service</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('service.list') }}">Services</a>
                            </li>
                            <li class="breadcrumb-item">Add Service</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Error Messages ] -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- [ Main Content ] start -->
        <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Service Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <div id="quill-editor" style="height: 200px;"></div>
                                        <textarea name="description" id="description" class="d-none"></textarea>
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Duration</label>
                                        <select name="duration" class="form-control select-user">
                                            <option value="">-- Select Duration --</option>
                                            @for ($minutes = 30; $minutes <= 1440; $minutes +=30)
                                                @php
                                                $hrs=floor($minutes / 60);
                                                $mins=$minutes % 60;
                                                $label='' ;
                                                if ($hrs> 0) {
                                                $label .= $hrs . ' hour' . ($hrs > 1 ? 's' : '');
                                                }
                                                if ($hrs > 0 && $mins > 0) {
                                                $label .= ' ';
                                                }
                                                if ($mins > 0) {
                                                $label .= $mins . ' minutes';
                                                }
                                                @endphp
                                                <option value="{{ $minutes }}">{{ $label }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                </div>

                                <!-- Staff -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Staff Member</label>
                                        <select name="staff_member[]" class="form-control select2-mash" multiple required>
                                            @foreach($staffUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Settings</h5>
                        </div>
                        <div class="card-body">
                            <!-- Category -->
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category" class="form-control category">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control select-user">
                                    <option value="{{ config('constants.status.active') }}" {{ old('status') == config('constants.status.active') ? 'selected' : '' }}>Active</option>
                                    <option value="{{ config('constants.status.inactive') }}" {{ old('status') == config('constants.status.inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Featured Image Upload -->
                            <div class="form-group">
                                <label>Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="thumbnail" id="addAvatarInput" accept=".jpg,.jpeg,.png,.gif">
                                        <label class="custom-file-label overflow-hidden" for="addAvatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported types: JPG, JPEG, PNG, GIF.</small>
                                @error('avatar')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                <!-- Image Preview -->
                                <div id="add-avatar-preview-container" class="row d-none mt-3">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="add-avatar-preview" class="card-img-top img-thumbnail h-100" alt="Avatar Preview">
                                            <button type="button" id="remove-add-avatar-preview" class="btn btn-sm btn-dark position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image" title="Remove image">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-0">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('service.list') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Section -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#pricing-tab" role="tab">
                                        <i class="feather icon-tag"></i> Pricing
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#gallery-tab" role="tab">
                                        <i class="feather icon-image"></i> Gallery
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#settings-tab" role="tab">
                                        <i class="feather icon-settings"></i> Settings
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="pricing-tab" role="tabpanel">
                                    @include('admin.service.partials.add.pricing')
                                </div>
                                <div class="tab-pane fade" id="gallery-tab" role="tabpanel">
                                    @include('admin.service.partials.add.gallery')
                                </div>
                                <div class="tab-pane fade" id="settings-tab" role="tabpanel">
                                    @include('admin.service.partials.add.settings')
                                </div>
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