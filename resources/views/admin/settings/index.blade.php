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
                            <li class="breadcrumb-item"><a href="#!">Settings</a></li>
                            <li class="breadcrumb-item"><a href="#!">Add Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <h5 class="card-header">Date/Time Format</h5>
                        <div class="card-body">
                            <!-- Date Format -->
                            <div class="form-group">
                                <label class="form-label">Date Format</label>
                                <select name="date_format" class="form-control select-user">
                                    @foreach($dateFormats as $key => $label)
                                        <option value="{{ $key }}" {{ (old('date_format') ?? $settings['date_format'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('date_format')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- DateTime Format -->
                            <div class="form-group">
                                <label class="form-label">Date/Time Format</label>
                                <select name="datetime_format" class="form-control select-user">
                                    @foreach($datetimeFormats as $key => $label)
                                        <option value="{{ $key }}" {{ (old('datetime_format') ?? $settings['datetime_format'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('datetime_format')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <h5 class="card-header">Owner Information</h5>
                        <div class="card-body">
                            <!-- Owner Phone Number -->
                            <div class="form-group">
                                <label class="form-label">Owner Phone Number</label>
                                <div class="input-group">
                                    <select class="form-control" name="code" style="max-width: 100px;">
                                        @foreach($phoneCountries as $country)
                                            <option value="{{ $country['code'] }}"
                                                {{ (old('code') ?? $settings['owner_country_code'] ?? '+91') == $country['code'] ? 'selected' : '' }}>
                                                {{ $country['code'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" class="form-control" name="owner_phone_number" placeholder="Enter phone number"
                                        value="{{ old('owner_phone_number') ?? $settings['owner_phone_number'] ?? '' }}" required>
                                </div>
                                @error('owner_phone_number')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Owner Email -->
                            <div class="form-group">
                                <label class="form-label">Owner Email</label>
                                <input type="email" class="form-control" name="owner_email" placeholder="Enter owner email"
                                    value="{{ old('owner_email') ?? $settings['owner_email'] ?? '' }}" required>
                                @error('owner_email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <h5 class="card-header">Timezone</h5>
                        <div class="card-body">
                            <!-- Timezone -->
                            <div class="form-group">
                                <label class="form-label">Timezone</label>
                                <select name="timezone" class="form-control select-user">
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone }}" {{ (old('timezone') ?? $settings['timezone'] ?? '') == $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <h5 class="card-header">Social Media Links</h5>
                        <div class="card-body">
                            <!-- Facebook -->
                            <label class="form-label">Facebook</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="fab fa-facebook-f"></i></span>
                                </div>
                                <input type="text" class="form-control" name="facebook" id="form-label" placeholder="Facebook link or username" aria-describedby="inputGroupPrepend" value="{{ old('facebook') ?? $settings['facebook'] ?? '' }}">
                            </div>
                            <br>
                            <!-- Linkedin -->
                            <label class="form-label">LinkedIn</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="fab fa-linkedin-in"></i></span>
                                </div>
                                <input type="text" class="form-control" name="linkedin" id="form-label" placeholder="LinkedIn link or username" aria-describedby="inputGroupPrepend" value="{{ old('linkedin') ?? $settings['linkedin'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header"><h5>Website Logo</h5></div>
                        <div class="card-body">
                            <!-- Website Logo -->
                            <div class="form-group">
                                <label class="form-label">Website Logo</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="website_logo" id="websiteLogoInput" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        <label class="custom-file-label overflow-hidden" for="websiteLogoInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported image types: JPG, JPEG, PNG, or GIF.</small>
                                @error('website_logo')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                {{-- Preview --}}
                                <div id="website-logo-preview-container" class="row mt-3 {{ !empty($settings['website_logo']) ? '' : 'd-none' }}">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="website-logo-preview"
                                                src="{{ !empty($settings['website_logo']) ? asset('storage/' . $settings['website_logo']) : asset('assets/images/no-image-available.png') }}"
                                                class="card-img-top img-thumbnail"
                                                alt="Logo Preview"
                                                style="object-fit: cover; height: 120px; width: 100%;">
                                            <button type="button"
                                                id="remove-website-logo-preview"
                                                class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                                title="Remove website logo"
                                                onclick="removeImage('website_logo')">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_website_logo" id="removeWebsiteLogoFlag" value="0">
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header"><h5>Website Favicon</h5></div>
                        <div class="card-body">                            
                            <!-- Favicon -->
                            <div class="form-group">
                                <label class="form-label">Favicon</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="favicon" id="faviconInput" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        <label class="custom-file-label overflow-hidden" for="faviconInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported image types: JPG, JPEG, PNG, or GIF.</small>
                                @error('favicon')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                {{-- Preview --}}
                                <div id="favicon-preview-container" class="row mt-3 {{ !empty($settings['favicon']) ? '' : 'd-none' }}">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="favicon-preview"
                                                src="{{ !empty($settings['favicon']) ? asset('storage/' . $settings['favicon']) : asset('assets/images/no-image-available.png') }}"
                                                class="card-img-top img-thumbnail"
                                                alt="Favicon Preview"
                                                style="object-fit: cover; height: 80px; width: 80px;">
                                            <button type="button"
                                                id="remove-favicon-preview"
                                                class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                                title="Remove favicon"
                                                onclick="removeImage('favicon')">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_favicon" id="removeFaviconFlag" value="0">
                            </div>
                            <!-- Submit -->
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
                            </div>
                        </div>
                    </div>
                </div>
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
