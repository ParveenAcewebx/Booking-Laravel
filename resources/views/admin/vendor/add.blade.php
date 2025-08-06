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
                            <h5 class="m-b-10">Add Vendor</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Vendor</a></li>
                            <li class="breadcrumb-item"><a href="#">Add Vendor</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <form action="{{ route('vendors.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Name" value="{{ old('username') }}">
                                        @error('username')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <div id="quill-editor" style="height: 200px;">{!! old('description') !!}</div>
                                        <textarea name="description" id="description" class="d-none">{!! old('description') !!}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                  <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}">
                                        @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                  <!-- Phone no -->
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select class="form-control @error('code') is-invalid @enderror" name="code" style="max-width: 100px;">
                                                @foreach($phoneCountries as $country)
                                                    <option value="{{ $country['code'] }}"
                                                        {{ old('code', '+91') == $country['code'] ? 'selected' : '' }}>
                                                        {{ $country['code'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" placeholder="Enter phone number" value="{{ old('phone_number') }}">
                                        </div>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#assignStaff" role="tab">
                                        <i class="feather icon-layers"></i> Assign Staff
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#stripeAccount" role="tab">
                                        <i class="fab fa-stripe"></i> Stripe
                                    </a>
                                </li>
                                
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show" id="stripeAccount" role="tabpanel">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="payment__is_live" name="stripe_mode" value="1"
                                                {{ old('stripe_mode') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="payment__is_live">Live Mode</label>
                                        </div>
                                    </div>

                                    <div class="stripe-test">
                                        <div class="form-group">
                                            <label>Test Site Key</label>
                                            <input type="text" name="stripe_test_site_key" class="form-control" value="{{ old('stripe_test_site_key') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Test Secret Key</label>
                                            <input type="text" name="stripe_test_secret_key" class="form-control" value="{{ old('stripe_test_secret_key') }}">
                                        </div>
                                    </div>

                                    <div class="stripe-live d-none">
                                        <div class="form-group">
                                            <label>Live Site Key</label>
                                            <input type="text" name="stripe_live_site_key" class="form-control" value="{{ old('stripe_live_site_key') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Live Secret Key</label>
                                            <input type="text" name="stripe_live_secret_key" class="form-control" value="{{ old('stripe_live_secret_key') }}">
                                        </div>
                                    </div>
                                </div>
                                <!-- Assign Staff Tab -->
                                <div class="tab-pane show active" id="assignStaff" role="tabpanel">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 font-weight-bold">Assigned Staff</h6>
                                        <button type="button" class="btn btn-sm btn-primary" id="addStaffButton">
                                            <i class="feather icon-plus"></i> Add Staff
                                        </button>
                                    </div>

                                    <div id="dayOffRepeater"></div>
                                    @include('admin.vendor.partials.showing-template')
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
                                <select name="status" id="status" class="form-control select-status @error('status') is-invalid @enderror">
                                    <option value="{{ config('constants.status.active') }}"
                                        {{ old('status', config('constants.status.active')) == config('constants.status.active') ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="{{ config('constants.status.inactive') }}"
                                        {{ old('status', config('constants.status.active')) == config('constants.status.inactive') ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>

                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Assigned Services</label>
                                <select class="form-control select-service" name="assigned_service[]" multiple>
                                    @foreach($allService as $service)
                                    <option value="{{ $service->id }}" {{ old('assigned_service') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Featured Image -->
                            <div class="form-group">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('thumbnail') is-invalid @enderror" name="thumbnail" id="addAvatarInput" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        <label class="custom-file-label overflow-hidden" for="addAvatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported image types: JPG, JPEG, PNG, or GIF.</small>
                                @error('thumbnail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Preview -->
                            <div id="add-avatar-preview-container" class="row d-none mt-3">
                                <div class="col-md-6 position-relative">
                                    <div class="card shadow-sm">
                                        <img id="add-avatar-preview" class="card-img-top img-thumbnail" alt="Avatar Preview">
                                        <button type="button" id="remove-add-avatar-preview" class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image" title="Remove image">&times;</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <!-- [ Main Content ] end -->

    </div>
</section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select-user').select2();

            let assignedStaff = @json($preAssignedStaffIds);
            let selectedStaff = new Set();

            function fetchAndDisplayServices(staffId, cardBody) {
                cardBody.find('.staff-services').remove();
                let servicesappend = cardBody.find('.addServices');
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
                            servicesappend.append(listHtml);
                        } else {
                            servicesappend.append('<div class="staff-services text-muted">No services assigned</div>');
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
                    $('.staff_not_found_outer').remove();
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

            function checkstaffablableornot() {
                let selectElement = $('#dayOffRepeater').find('.select-user');
                if (selectElement.hasClass('select-user')) {
                    let appendhasOptions;
                    selectElement.each(function() {
                        let selectElement = $(this);
                        let options = selectElement.find('option');
                        let hasOptions = options.filter(function() {
                            return $(this).val() && !$(this).prop('disabled');
                        }).length > 1;
                        appendhasOptions = hasOptions
                    });

                    if (appendhasOptions) {
                        appendStaffTemplate();
                    } else {
                        $('.staff_not_found_outer').remove();
                        if (!$('.staff_not_found').length) {
                            $('#dayOffRepeater').append(`
                            <div class="card border shadow-sm day-off-entry mb-3 staff_not_found_outer">
                                <div class="card-body position-relative">
                                    <div class="form-row pt-2">
                                        <div class="form-group col-md-12 mb-1">
                                            <div class="form-group col-md-12 mb-2 mt-2 staff_not_found">
                                                No staff available, Please add a new one first 
                                                <a href="{{ route('staff.list') }}" class="text-center">Add Staff</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            `);
                        }
                    }

                } else {
                    let staff_not_found_outer = $('#dayOffRepeater').find('.staff_not_found_outer');
                    if (staff_not_found_outer.hasClass('staff_not_found_outer')) {
                        $('.staff_not_found_outer').remove();
                        $('#dayOffRepeater .card.border.shadow-sm.day-off-entry.mb-3').remove();
                    }
                    appendStaffTemplate();
                }
            }

            // Add new staff manually
            document.getElementById('addStaffButton').addEventListener('click', function() {
                checkstaffablableornot();
            });
        });
    </script>
@endsection