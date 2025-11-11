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
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Service</a></li>
                            <li class="breadcrumb-item"><a href="#!">Edit Service</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
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

                            <!-- Name -->
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $service->name) }}">
                                @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label>Description</label>
                                <div id="quill-editor" style="height: 200px;"></div>
                                <textarea name="description" id="description" class="d-none">{{ old('description', $service->description) }}</textarea>
                                @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Duration & Staff -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Select Vendor <span class="text-danger"></span></label>
                                    <select name="vendor[]" class="form-control vendor" multiple>
                                        <option value="">-- Select Vendor --</option>
                                        @foreach($activeVendor as $Vendor)
                                        <option value="{{ $Vendor->id }}"
                                            {{ in_array($Vendor->id, old('vendor', $getVendorIds ?? [])) ? 'selected' : '' }}>
                                            {{ $Vendor->name }}
                                        </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-md-6">
                                    <label>Duration <span class="text-danger">*</span></label>
                                    <select name="duration" class="form-control select-user @error('duration') is-invalid @enderror">
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
                                    @error('duration')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- <div class="form-group col-md-6">
                                    <label>Staff Member <span class="text-danger">*</span></label>
                                    <select name="staff_member[]" class="form-control select2-mash @error('staff_member') is-invalid @enderror" multiple >
                                        @php
                                        $selectedStaff = old('staff_member', $associatedStaffIds ?? []);
                                        @endphp
                                        @foreach($staffUsers as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, $selectedStaff) ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('staff_member')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div> -->
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#pricing" role="tab"><i class="feather icon-tag"></i> Pricing</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#gallery" role="tab"><i class="feather icon-image"></i> Gallery</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#settings" role="tab"><i class="feather icon-settings"></i> Settings</a></li>
                            </ul>
                            <div class="tab-content">
                                @include('admin.service.partials.edit.pricing')
                                @include('admin.service.partials.edit.gallery')
                                @include('admin.service.partials.edit.settings')
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
                                <label>Category <span class="text-danger"></span></label>
                                <select name="category" class="form-control category @error('category') is-invalid @enderror">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category', $service->category) == $category->id ? 'selected' : '' }}>
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
                                <select name="status" class="form-control select-user @error('status') is-invalid @enderror">
                                    @foreach($statuses as $label => $value)
                                    <option value="{{ $value }}" {{ old('status', $service->status) == $value ? 'selected' : '' }}>
                                        {{ ucfirst($label) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Thumbnail -->
                            <div class="form-group">
                                <label for="thumbnail">Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('thumbnail') is-invalid @enderror"
                                            name="thumbnail" id="thumbnailInput"
                                            accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        <label class="custom-file-label overflow-hidden" for="thumbnailInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported types: JPG, JPEG, PNG, GIF.</small>
                                @error('thumbnail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <div id="edit-thumbnail-preview-container" class="row mt-3 {{ $service->thumbnail ? '' : 'd-none' }}">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="edit-thumbnail-preview"
                                                src="{{ asset('storage/' . $service->thumbnail) }}"
                                                class="card-img-top img-thumbnail"
                                                alt="Thumbnail Preview"
                                                style="object-fit: cover; height: 120px; width: 100%;">
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
            if (parseInt(selectedValue) === i) option.selected = true;
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