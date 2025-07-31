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
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                               name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name') }}"
                                               placeholder="Enter service name">
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <div id="quill-editor" style="height: 200px;">{!! old('description') !!}</div>
                                        <textarea name="description"
                                                  id="description"
                                                  class="d-none @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Duration <span class="text-danger">*</span></label>
                                        <select name="duration"
                                                class="form-control select-user @error('duration') is-invalid @enderror">
                                            <option value="">-- Select Duration --</option>
                                            @for ($minutes = 30; $minutes <= 1440; $minutes += 30)
                                                @php
                                                    $hrs = floor($minutes / 60);
                                                    $mins = $minutes % 60;
                                                    $label = ($hrs ? $hrs . ' hour' . ($hrs > 1 ? 's' : '') : '') .
                                                             ($hrs && $mins ? ' ' : '') .
                                                             ($mins ? $mins . ' minutes' : '');
                                                @endphp
                                                <option value="{{ $minutes }}"
                                                    {{ old('duration') == $minutes ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('duration')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Staff -->
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Staff Member <span class="text-danger">*</span></label>
                                        <select name="staff_member[]"
                                                class="form-control select2-mash @error('staff_member') is-invalid @enderror"
                                                multiple>
                                            @foreach($staffUsers as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ collect(old('staff_member'))->contains($user->id) ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('staff_member')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> -->
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
                                <label>Category <span class="text-danger">*</span></label>
                                <select name="category"
                                        class="form-control category @error('category') is-invalid @enderror">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status"
                                        class="form-control select-user @error('status') is-invalid @enderror">
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
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
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
                                        <input type="file"
                                               class="custom-file-input @error('thumbnail') is-invalid @enderror"
                                               name="thumbnail"
                                               id="addAvatarInput"
                                               accept=".jpg,.jpeg,.png,.gif">
                                        <label class="custom-file-label overflow-hidden" for="addAvatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported types: JPG, JPEG, PNG, GIF.</small>
                                @error('thumbnail')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
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
