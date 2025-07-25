@extends('admin.layouts.app')

@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">

        <!-- Breadcrumb -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Vendor</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Vendor</a></li>
                            <li class="breadcrumb-item"><a href="#">Edit Vendor</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Vendor Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="username"
                                            value="{{ old('username', $vendor->name) }}" placeholder="Name" required>
                                        @error('username')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $vendor->email) }}" placeholder="Email" required>
                                        @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <div id="quill-editor" style="height: 200px;">{!! old('description', $vendor->description) !!}</div>
                                        <textarea name="description" id="description" class="d-none">{{ old('description', $vendor->description) }}</textarea>
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
                            <!-- Status -->
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" id="status" class="form-control select-user">
                                    <option value="{{ config('constants.status.active') }}"
                                        {{ $vendor->status == config('constants.status.active') ? 'selected' : '' }}>Active</option>
                                    <option value="{{ config('constants.status.inactive') }}"
                                        {{ $vendor->status == config('constants.status.inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="form-group">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="thumbnail" id="avatarInput"
                                            accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        <label class="custom-file-label overflow-hidden" for="avatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported image types: JPG, JPEG, PNG, GIF.</small>
                                @error('thumbnail')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                <!-- Preview -->
                                <div id="avatar-preview-container"
                                    class="row mt-3 {{ !empty($vendor->thumbnail) ? '' : 'd-none' }}">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="avatar-preview"
                                                src="{{ !empty($vendor->thumbnail) ? asset('storage/' . $vendor->thumbnail) : asset('assets/images/no-image-available.png') }}"
                                                class="card-img-top img-thumbnail"
                                                style="object-fit: cover; height: 120px; width: 100%;">
                                            <button type="button" id="remove-avatar-preview"
                                                class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                                title="Remove avatar">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">
                            </div>

                            <!-- Submit Button -->
                            <div class="text-right mt-0">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stripe + Staff Tabs -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card mt-3">
                        <div class="card-body">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#stripeAccount" role="tab">
                                        <i class="fab fa-stripe"></i> Stripe
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#assignStaff" role="tab">
                                        <i class="feather icon-layers"></i> Assign Staff
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- Stripe Tab -->
                                <div class="tab-pane fade show active" id="stripeAccount" role="tabpanel">
                                    @include('admin.vendor.partials.stripe-credentials')
                                </div>

                                <!-- Assign Staff Tab -->
                                <div class="tab-pane fade" id="assignStaff" role="tabpanel">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 font-weight-bold">Assigned Staff</h6>
                                        <button type="button" class="btn btn-sm btn-primary" id="addStaffButton">
                                            <i class="feather icon-plus"></i> Add Staff
                                        </button>
                                    </div>

                                    <div id="dayOffRepeater"></div>
                                    <!-- Staff Template -->
                                    @include('admin.vendor.partials.showing-template')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select-user').select2();

        let assignedStaff = @json($preAssignedStaffIds);
        let selectedStaff = new Set();

        function fetchAndDisplayServices(staffId, $cardBody) {
            $cardBody.find('.staff-services').remove();
            if (!staffId) return;

            $.ajax({
                url: `/admin/vendors/${staffId}/services`,
                type: 'GET',
                success: function(services) {
                    if (services.length > 0) {
                        let listHtml = '<div class="staff-services">';
                        services.forEach(service => {
                            listHtml += `<span class="badge badge-service">${service}</span>`;
                        });
                        listHtml += '</div>';
                        $cardBody.append(listHtml);
                    } else {
                        $cardBody.append('<div class="staff-services text-muted">No services assigned</div>');
                    }
                }
            });
        }

        function attachStaffChangeHandler($select) {
            $select.on('change', function() {
                let staffId = $(this).val();
                let prevId = $select.data('prev');

                if (prevId) selectedStaff.delete(prevId);
                if (staffId) selectedStaff.add(String(staffId));
                $select.data('prev', staffId);

                refreshOptions();
                fetchAndDisplayServices(staffId, $(this).closest('.card-body'));
            });
        }

        function attachDeleteHandler($btn) {
            $btn.on('click', function() {
                let $row = $(this).closest('.card');
                let staffId = $row.find('.select-user').val();

                if (staffId) selectedStaff.delete(String(staffId));
                $row.remove();
                refreshOptions();
            });
        }

        function refreshOptions() {
            $('.select-user').each(function() {
                let $this = $(this);
                let currentVal = $this.val();

                $this.find('option').each(function() {
                    let optionVal = $(this).attr('value');
                    if (selectedStaff.has(String(optionVal)) && optionVal !== currentVal) {
                        $(this).attr('disabled', true).hide();
                    } else {
                        $(this).attr('disabled', false).show();
                    }
                });

                $this.trigger('change.select2');
            });
        }

        function appendStaffTemplate(preSelectedId = null) {
            let template = document.getElementById('staffTemplate').content.cloneNode(true);
            document.getElementById('dayOffRepeater').appendChild(template);

            let $newSelect = $('#dayOffRepeater').find('.select-user').last();
            $newSelect.select2();

            if (preSelectedId) {
                $newSelect.val(String(preSelectedId)).trigger('change.select2');
                selectedStaff.add(String(preSelectedId));
            }

            attachStaffChangeHandler($newSelect);
            attachDeleteHandler($('#dayOffRepeater').find('.delete-row').last());
            refreshOptions();

            if (preSelectedId) {
                fetchAndDisplayServices(preSelectedId, $newSelect.closest('.card-body'));
            }
        }

        // Auto append preassigned staff
        if (assignedStaff.length > 0) {
            assignedStaff.forEach(id => appendStaffTemplate(id));
        }

        // Add new staff manually
        document.getElementById('addStaffButton').addEventListener('click', function() {
            appendStaffTemplate();
        });
    });
</script>
@endsection