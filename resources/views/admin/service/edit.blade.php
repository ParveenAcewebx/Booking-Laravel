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
                            <h5 class="m-b-10">Edit Service</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('service.list') }}">Services</a>
                            </li>
                            <li class="breadcrumb-item">Edit Service</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

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
        <form action="{{ route('service.update', $service) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Service Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $service->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <div id="quill-editor" style="height: 200px;"></div>
                                <textarea name="description" id="description" class="d-none">{{ old('description', $service->description) }}</textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Duration</label>
                                    <select name="duration" class="form-control select-user">
                                        <option value="">-- Select Duration --</option>
                                        @for ($minutes = 30; $minutes <= 1440; $minutes +=30)
                                            @php
                                            $hrs=floor($minutes / 60);
                                            $mins=$minutes % 60;
                                            $label='' ;
                                            if ($hrs> 0) $label .= $hrs . ' hour' . ($hrs > 1 ? 's' : '');
                                            if ($hrs > 0 && $mins > 0) $label .= ' ';
                                            if ($mins > 0) $label .= $mins . ' minutes';
                                            @endphp
                                            <option value="{{ $minutes }}" {{ old('duration', $service->duration) == $minutes ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Staff Member</label>
                                    <select name="staff_member[]" class="form-control select2-mash" multiple required>
                                        @php
                                        $selectedStaff = old('staff_member', $associatedStaffIds ?? []);
                                        @endphp
                                        @foreach($staffUsers as $user)
                                        <option value="{{ $user->id }}" {{ in_array($user->id, $selectedStaff) ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                        @endforeach
                                    </select>
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
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category" class="form-control category">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $service->category == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control select-user" required>
                                    @foreach($statuses as $label => $value)
                                    <option value="{{ $value }}" {{ $service->status == $value ? 'selected' : '' }}>{{ ucfirst($label) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="thumbnail" class="form-label">Featured Image</label>

                                <div class="custom-file">
                                    <input type="file" name="thumbnail" class="custom-file-input" id="thumbnailInput"
                                        accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                    <label class="custom-file-label" for="thumbnailInput">Choose file...</label>
                                    <div class="invalid-feedback">Please upload a valid thumbnail.</div>
                                </div>
                                <small class="form-text text-muted">
                                    Supported image types: JPG, JPEG, PNG, or GIF.
                                </small>
                                {{-- Preview Card --}}
                                <div id="edit-thumbnail-preview-container" class="row mt-3 {{ !empty($service->thumbnail) ? '' : 'd-none' }}">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="edit-thumbnail-preview"
                                                src="{{ $service->thumbnail ? asset('storage/' . $service->thumbnail) : asset('assets/images/no-image-available.png') }}"
                                                class="card-img-top img-thumbnail"
                                                alt="Thumbnail Preview"
                                                style="object-fit: contain; height: 120px; width: 100%;">

                                            {{-- Remove Button Like Screenshot --}}
                                            <button type="button"
                                                id="remove-preview"
                                                class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                                title="Remove image">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_thumbnail" id="removeThumbnailFlag" value="0">
                            </div>
                            <div class="text-right mt-0">
                                <button type="submit" class="btn btn-primary">Update</button>
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
                                    <a class="nav-link active" data-toggle="tab" href="#pricing" role="tab">
                                        <i class="feather icon-tag"></i> Pricing
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#gallery" role="tab">
                                        <i class="feather icon-image"></i> Gallery
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
                                        <i class="feather icon-settings"></i> Settings
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                @include('admin.service.partials.edit.pricing')
                                @include('admin.service.partials.edit.gallery')
                                @include('admin.service.partials.edit.settings')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- [ Main Content ] end -->
    </div>
</section>
<style>
    .gallery-preview img {
        object-fit: cover;
    }
</style>

<script>
    function populateCancellingValues(unit, selectedValue = null) {
        const valueSelect = document.getElementById("cancelling_value");
        valueSelect.innerHTML = "";

        let max = unit === "hours" ? 24 : 30;
        for (let i = 1; i <= max; i++) {
            const option = document.createElement("option");
            option.value = i;
            option.text = i;
            if (parseInt(selectedValue) === i) {
                option.selected = true;
            }
            valueSelect.appendChild(option);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const unitSelect = document.getElementById("cancelling_unit");
        const selectedValue = document.getElementById("cancel_value")?.value || null;

        populateCancellingValues(unitSelect.value, selectedValue);

        unitSelect.addEventListener("change", function() {
            populateCancellingValues(this.value);
        });
    });
</script>

@endsection