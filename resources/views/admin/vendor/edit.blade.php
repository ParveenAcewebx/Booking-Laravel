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
                <!-- Left -->
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
                                        <label class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username', $vendor->name) }}">
                                        @error('username')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $vendor->email) }}">
                                        @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <div id="quill-editor" style="height: 200px;">{!! old('description', $vendor->description) !!}</div>
                                        <textarea name="description" id="description" class="d-none">{{ old('description', $vendor->description) }}</textarea>
                                        @error('description')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <!-- Nav Tabs -->
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
                                    @include('admin.vendor.partials.showing-template')

                                    <!-- Hidden JSON with staff data -->
                                    <input type="hidden" id="editDayOffData" value='@json($staffAssociation)'>

                                    <!-- Switch Primary Staff Section -->
                                    <div class="col-md-5 mt-4 p-0">
                                        <h6 class="font-weight-bold">Switch Primary Staff</h6>
                                        <div class="form-group">
                                            <select name="primary_staff" class="form-control select-users">
                                                <option value="">--- Select Primary Staff ---</option>
                                                @foreach($availableStaff->whereIn('id', $preAssignedStaffIds) as $staff)
                                                <option value="{{ $staff->id }}"
                                                    {{ $currentPrimary && $currentPrimary->user_id == $staff->id ? 'selected' : '' }}>
                                                    {{ $staff->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right -->
                <div class="col-md-4 order-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>Settings</h5>
                        </div>
                        <div class="card-body">
                            <!-- Status -->
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control select-status @error('status') is-invalid @enderror">
                                    <option value="{{ config('constants.status.active') }}" {{ old('status', $vendor->status) == config('constants.status.active') ? 'selected' : '' }}>Active</option>
                                    <option value="{{ config('constants.status.inactive') }}" {{ old('status', $vendor->status) == config('constants.status.inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Assigned Services</label>
                                <select class="form-control select-service" name="assigned_service[]" multiple>
                                    @foreach($allService as $service)
                                    <option value="{{ $service->id }}"
                                        {{ in_array($service->id, old('assigned_service', $gsd ?? [])) ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- Thumbnail Upload -->
                            <div class="form-group">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend"><span class="input-group-text">Upload</span></div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="thumbnail" id="avatarInput"
                                            accept=".jpg,.jpeg,.png,.gif,image/*">
                                        <label class="custom-file-label" for="avatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported image types: JPG, JPEG, PNG, GIF.</small>
                                @error('thumbnail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <!-- Preview -->
                                <div id="avatar-preview-container" class="row mt-3 {{ $vendor->thumbnail ? '' : 'd-none' }}">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="avatar-preview"
                                                src="{{ asset('storage/' . $vendor->thumbnail) }}"
                                                class="card-img-top img-thumbnail"
                                                style="object-fit: cover; height: 120px; width: 100%;">
                                            <button type="button" id="remove-avatar-preview"
                                                class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                                title="Remove image">&times;</button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">
                            </div>

                            <!-- Submit -->
                            <div class="text-right mt-0">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.select-user').select2();
    // $('.select-service').select2();

    let assignedStaff = @json($preAssignedStaffIds ?? []); // preassigned IDs
    let selectedStaff = new Set();

    // Toggle primary badge + delete/readonly state
    function togglePrimaryState($select) {
        let isPrimary = $select.find('option:selected').data('primary-staff') == 1;
        let $card = $select.closest('.card');
        let $deleteBtn = $card.find('.delete-row');
        let $badge = $card.find('.primary-badge-container');

        if (isPrimary) {
            // Show badge
            $badge.show();

            // Disable delete
            $deleteBtn.prop('disabled', true).hide();

            // Disable select but keep value submitted
            $select.prop('disabled', true).trigger('change.select2');
            if (!$select.next('input[type=hidden][name="select_staff[]"]').length) {
                $('<input>', {
                    type: 'hidden',
                    name: 'select_staff[]',
                    value: $select.val()
                }).insertAfter($select);
            }
        } else {
            // Hide badge
            $badge.hide();

            // Enable delete
            $deleteBtn.prop('disabled', false).show();

            // Enable select
            $select.prop('disabled', false).trigger('change.select2');

            // Remove hidden input
            $select.next('input[type=hidden][name="select_staff[]"]').remove();
        }
    }

    // Fetch services and display badges
    function fetchAndDisplayServices(staffId, cardBody) {
        cardBody.find('.staff-services').remove();
        let servicesAppend = cardBody.find('.addServices');
        cardBody = servicesAppend.length > 0 ? servicesAppend : cardBody;

        if (!staffId) return;

        $.ajax({
            url: `/admin/vendors/${staffId}/services`,
            type: 'GET',
            success: function (services) {
                if (services.length > 0) {
                    let listHtml = '<div class="staff-services">';
                    services.forEach(service => {
                        listHtml += `<span class="badge badge-service">${service}</span>`;
                    });
                    listHtml += '</div>';
                    cardBody.append(listHtml);
                } else {
                    cardBody.append('<div class="staff-services text-muted">No services assigned</div>');
                }
            }
        });
    }

    // Staff change handler
    function attachStaffChangeHandler($select) {
        $select.on('change', function () {
            let staffId = $(this).val();
            let prevId = $select.data('prev');

            if (prevId) selectedStaff.delete(prevId);
            if (staffId) selectedStaff.add(String(staffId));
            $select.data('prev', staffId);

            togglePrimaryState($select);
            refreshOptions();
            fetchAndDisplayServices(staffId, $(this).closest('.card-body'));
        });
    }

    // Delete button handler
    function attachDeleteHandler($btn) {
        $btn.on('click', function () {
            let $row = $(this).closest('.card');
            let staffId = $row.find('.select-user').val();
            if (staffId) selectedStaff.delete(String(staffId));
            $row.remove();
            refreshOptions();
        });
    }

    // Refresh options to prevent duplicates
    function refreshOptions() {
        $('.select-user').each(function () {
            let $this = $(this);
            let currentVal = $this.val();
            $this.find('option').each(function () {
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

    // Append template
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

        togglePrimaryState($newSelect);

        if (preSelectedId) {
            fetchAndDisplayServices(preSelectedId, $newSelect.closest('.card-body'));
        }

        refreshOptions();
    }

    // Initialize preassigned staff
    if (assignedStaff.length > 0) {
        assignedStaff.forEach(id => appendStaffTemplate(id));
    }

    // Add staff button
    document.getElementById('addStaffButton').addEventListener('click', function () {
        appendStaffTemplate();
    });
});
</script>
@endsection