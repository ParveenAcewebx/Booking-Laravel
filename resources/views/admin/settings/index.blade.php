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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header"><h5>Settings</h5></div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Date Format -->
                                <div class="col-md-6">
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
                                </div>

                                <!-- DateTime Format -->
                                <div class="col-md-6">
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

                                <!-- Timezone -->
                                <div class="col-md-6">
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

                                <!-- Phone Number -->
                                <div class="col-md-6">
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
                                </div>

                                <!-- Owner Email -->
                                <div class="col-md-6">
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
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4 order-md-2">
                    <div class="card">
                        <div class="card-header"><h5>Information</h5></div>
                        <div class="card-body">
                            <!-- Facebook -->
                            <div class="form-group">
                                <label class="form-label">Facebook</label>
                                <input type="text" class="form-control" name="facebook" placeholder="Facebook link or username"
                                    value="{{ old('facebook') ?? $settings['facebook'] ?? '' }}">
                            </div>

                            <!-- Linkedin -->
                            <div class="form-group">
                                <label class="form-label">LinkedIn</label>
                                <input type="text" class="form-control" name="linkedin" placeholder="LinkedIn link or username"
                                    value="{{ old('linkedin') ?? $settings['linkedin'] ?? '' }}">
                            </div>

                            <!-- Website Logo -->
                            <div class="form-group">
                                <label class="form-label">Website Logo</label>
                                <input type="file" class="form-control-file" name="website_logo">
                                @error('website_logo')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                @if(!empty($settings['website_logo']))
                                    <img src="{{ asset('storage/' . $settings['website_logo']) }}" alt="Logo" class="img-fluid mt-2" style="max-height: 100px;">
                                @endif
                            </div>

                            <!-- Favicon -->
                            <div class="form-group">
                                <label class="form-label">Favicon</label>
                                <input type="file" class="form-control-file" name="favicon">
                                @error('favicon')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                @if(!empty($settings['favicon']))
                                    <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon" class="img-fluid mt-2" style="max-height: 50px;">
                                @endif
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
@endsection
