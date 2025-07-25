@extends('admin.layouts.app')

@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">All Settings</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item">Settings</li>
                            <li class="breadcrumb-item">Add Settings</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data" class="settings-form">
            @csrf
            <div class="row">
                <!-- Site Identity -->
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header"><h5>Site Identity</h5></div>
                        <div class="card-body">
                            <!-- Site Title -->
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Site Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('site_title') is-invalid @enderror"
                                    name="site_title" placeholder="Enter Site Title"
                                    value="{{ old('site_title', $settings['site_title'] ?? '') }}" >
                                @error('site_title')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Website Logo -->
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Website Logo <span class="text-danger">*</span></label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend"><span class="input-group-text">Upload</span></div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('website_logo') is-invalid @enderror" name="website_logo" id="websiteLogoInput" accept="image/*">
                                        <label class="custom-file-label" for="websiteLogoInput">Choose file...</label>
                                    </div>
                                </div>
                                @error('website_logo')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Preview -->
                            @if (!empty($settings['website_logo']))
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['website_logo']) }}" height="80" alt="Logo">
                            </div>
                            @endif

                            <input type="hidden" name="remove_website_logo" id="removeWebsiteLogoFlag" value="0">

                            <!-- Favicon -->
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Favicon <span class="text-danger">*</span></label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend"><span class="input-group-text">Upload</span></div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('favicon') is-invalid @enderror" name="favicon" id="faviconInput" accept="image/*">
                                        <label class="custom-file-label" for="faviconInput">Choose file...</label>
                                    </div>
                                </div>
                                @error('favicon')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            @if (!empty($settings['favicon']))
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['favicon']) }}" height="40" alt="Favicon">
                            </div>
                            @endif

                            <input type="hidden" name="remove_favicon" id="removeFaviconFlag" value="0">
                        </div>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header"><h5>Date & Time</h5></div>
                        <div class="card-body">
                            <!-- Date Format -->
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Date Format</label>
                                <select name="date_format" class="form-control select-user @error('date_format') is-invalid @enderror">
                                    @foreach($dateFormats as $key => $label)
                                        <option value="{{ $key }}" {{ old('date_format', $settings['date_format'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('date_format')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Time Format -->
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Time Format</label>
                                <select name="time_format" class="form-control select-user @error('time_format') is-invalid @enderror">
                                    <option value="H:i" {{ old('time_format', $settings['time_format'] ?? '') == 'H:i' ? 'selected' : '' }}>24-Hour (14:30)</option>
                                    <option value="h:i A" {{ old('time_format', $settings['time_format'] ?? '') == 'h:i A' ? 'selected' : '' }}>12-Hour (02:30 PM)</option>
                                </select>
                                @error('time_format')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Timezone -->
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Timezone</label>
                                <select name="timezone" class="form-control select-user @error('timezone') is-invalid @enderror">
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone }}" {{ old('timezone', $settings['timezone'] ?? '') == $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Owner Info -->
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header"><h5>Owner Information</h5></div>
                        <div class="card-body">
                            <!-- Owner Phone -->
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Owner Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control @error('code') is-invalid @enderror" name="code" style="max-width: 100px;">
                                        @foreach($phoneCountries as $country)
                                            <option value="{{ $country['code'] }}"
                                                {{ old('code', $settings['owner_country_code'] ?? '+91') == $country['code'] ? 'selected' : '' }}>
                                                {{ $country['code'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" class="form-control @error('owner_phone_number') is-invalid @enderror"
                                        name="owner_phone_number" placeholder="Enter phone number"
                                        value="{{ old('owner_phone_number', $settings['owner_phone_number'] ?? '') }}" >
                                </div>
                                @error('owner_phone_number')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Owner Email -->
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Owner Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('owner_email') is-invalid @enderror"
                                    name="owner_email" placeholder="Enter owner email"
                                    value="{{ old('owner_email', $settings['owner_email'] ?? '') }}">
                                @error('owner_email')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header"><h5>Social Media Links</h5></div>
                        <div class="card-body">
                            @php
                                $socials = ['facebook', 'linkedin', 'instagram', 'x_twitter'];
                            @endphp
                            @foreach($socials as $social)
                            <div class="form-group">
                                <label class="form-label font-weight-bold text-capitalize">{{ str_replace('_', ' ', $social) }}</label>
                                <input type="text" class="form-control @error($social) is-invalid @enderror"
                                    name="{{ $social }}" placeholder="{{ ucfirst($social) }} link or username"
                                    value="{{ old($social, $settings[$social] ?? '') }}">
                                @error($social)
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-right settings-btn">
                <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
            </div>
        </form>
    </div>
</section>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        // Show Toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            timeOut: "4000",
            positionClass: "toast-top-right"
        };
        @if(session('success')) toastr.success("{{ session('success') }}"); @endif
        @if(session('error')) toastr.error("{{ session('error') }}"); @endif
        @if(session('info')) toastr.info("{{ session('info') }}"); @endif
        @if(session('warning')) toastr.warning("{{ session('warning') }}"); @endif

        // Update file preview when changed
        setupImagePreview('websiteLogoInput', 'website-logo-preview', 'website-logo-preview-container', 'removeWebsiteLogoFlag');
        setupImagePreview('faviconInput', 'favicon-preview', 'favicon-preview-container', 'removeFaviconFlag');
    });

    function setupImagePreview(inputId, imgId, containerId, flagId) {
        const input = document.getElementById(inputId);
        const previewImg = document.getElementById(imgId);
        const container = document.getElementById(containerId);
        const removeFlag = document.getElementById(flagId);
        const label = document.querySelector(`label[for="${inputId}"]`);

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                previewImg.src = URL.createObjectURL(file);
                container.classList.remove('d-none');
                label.innerText = file.name;
                removeFlag.value = 0;
            } else {
                // In case file was deselected
                container.classList.add('d-none');
                previewImg.src = '';
                label.innerText = 'Choose file...';
                removeFlag.value = 1;
            }
        });
    }

    function removeImage(type) {
        if (type === 'website_logo') {
            document.getElementById('website-logo-preview-container').classList.add('d-none');
            document.getElementById('websiteLogoInput').value = '';
            document.getElementById('removeWebsiteLogoFlag').value = 1;
            document.querySelector('label[for="websiteLogoInput"]').innerText = 'Choose file...';
        } else if (type === 'favicon') {
            document.getElementById('favicon-preview-container').classList.add('d-none');
            document.getElementById('faviconInput').value = '';
            document.getElementById('removeFaviconFlag').value = 1;
            document.querySelector('label[for="faviconInput"]').innerText = 'Choose file...';
        }
    }
</script>

@endsection
