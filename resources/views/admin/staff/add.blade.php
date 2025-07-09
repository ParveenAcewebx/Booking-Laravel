@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Add Staff</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('staff.list') }}">Staff</a></li>
                            <li class="breadcrumb-item">Add Staff</li>
                        </ul>
                    </div>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#user-details" role="tab">
                                    <i class="feather icon-info"></i> User Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#assigned-services" role="tab">
                                    <i class="feather icon-briefcase"></i> Assigned Services
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#working-days" role="tab">
                                    <i class="feather icon-clock"></i> Work Hours
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#days-off" role="tab">
                                    <i class="feather icon-calendar"></i> Days Off
                                </a>
                            </li>
                        </ul>

                        <form method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content">

                                {{-- TAB: USER DETAILS --}}
                                <div class="tab-pane fade show active" id="user-details" role="tabpanel">
                                    <div class="row">
                                        {{-- Avatar --}}
                                        <div class="col-md-12 mb-3 text-center">
                                            <img src="{{ asset('assets/images/no-image-available.png') }}" class="img-radius mb-3" width="100" alt="User Avatar">
                                            <div class="custom-file mx-auto">
                                                <input type="file" class="custom-file-input" name="avatar" id="avatar">
                                                <label class="custom-file-label" for="avatar">Choose file...</label>
                                            </div>
                                        </div>

                                        {{-- Name --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name:</label>
                                                <input type="text" class="form-control" name="name" required>
                                            </div>
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email:</label>
                                                <input type="email" class="form-control" name="email" required>
                                            </div>
                                        </div>

                                        {{-- Password --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Password:</label>
                                                <input type="password" class="form-control" name="password" required>
                                            </div>
                                        </div>

                                        {{-- Confirm Password --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Confirm Password:</label>
                                                <input type="password" class="form-control" name="password_confirmation" required>
                                                <div id="password-error"></div>
                                            </div>
                                        </div>

                                        {{-- Phone Number --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Phone Number</label>
                                                <div class="input-group">
                                                    <select class="form-control" name="code" style="max-width: 100px;">
                                                        @foreach($phoneCountries as $country)
                                                        <option value="{{ $country['code'] }}" {{ $country['code'] == '+91' ? 'selected' : '' }}>
                                                            {{ $country['code'] }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" class="form-control" name="phone_number" placeholder="Enter phone number" required>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Role --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Role:</label>
                                                <select class="form-control select_role" name="role" required>
                                                    <option value="">Select Role</option>
                                                    @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Status --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label d-block">Status</label>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="status" id="status" value="{{ config('constants.status.active') }}" checked>
                                                    <label class="custom-control-label" for="status">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB: ASSIGNED SERVICES --}}
                                <div class="tab-pane fade" id="assigned-services" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-left">Services</th>
                                                    <th class="text-center">Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($services as $service)
                                                <tr>
                                                    <td>
                                                        <div class="form-group mb-0">
                                                            <label class="form-check-label">
                                                                {{ $service->name }} @if($service->price) - ${{ $service->price }} @endif
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="text"
                                                            name="assigned_services[{{ $service->id }}][price]"
                                                            class="form-control form-control-sm text-center"
                                                            value="{{$service->currency}} ${{ number_format($service->price, 2) }}" disabled>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">No services available</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                {{-- TAB: WORK HOURS --}}
                                <div class="tab-pane fade" id="working-days" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="accordion" id="workingHoursAccordion">
                                                @foreach($weekDays as $index => $day)
                                                @php
                                                $daySlug = Str::slug($day);
                                                $collapseId = 'collapse' . ucfirst($daySlug);
                                                $headingId = 'heading' . ucfirst($daySlug);
                                                $isFirst = $index === 0;
                                                @endphp

                                                <div class="card mb-1 border">
                                                    <div class="d-flex justify-content-between align-items-center border-bottom px-3 py-2" id="{{ $headingId }}">
                                                        <div class="d-flex flex-grow-1 align-items-center justify-content-between">
                                                            <span class="font-weight-bold">{{ $day }}</span>

                                                            <div class="ml-auto d-flex align-items-center">
                                                                @if($day === 'Monday')
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input apply-to-all-days" id="applyAllDaysCheckbox" data-day="{{ $daySlug }}">
                                                                    <label class="custom-control-label ml-1 mb-0 medium" for="applyAllDaysCheckbox">Apply to all days</label>
                                                                </div>
                                                                @endif

                                                                <div class="chevron-toggle ml-3"
                                                                    style="cursor:pointer;"
                                                                    data-toggle="collapse"
                                                                    data-target="#{{ $collapseId }}"
                                                                    data-parent="#workingHoursAccordion"
                                                                    aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                                                    aria-controls="{{ $collapseId }}">
                                                                    <i class="feather {{ $isFirst ? 'icon-chevron-up' : 'icon-chevron-down' }}"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="{{ $collapseId }}"
                                                        class="collapse {{ $isFirst ? 'show' : '' }}"
                                                        aria-labelledby="{{ $headingId }}"
                                                        data-parent="#workingHoursAccordion">
                                                        <div class="card-body pt-2 pb-2 px-3">
                                                            <div class="d-flex">
                                                                {{-- Start Time --}}
                                                                <select class="form-control form-control-sm w-auto start-time select_start_time"
                                                                    name="working_days[{{ $daySlug }}][start]">
                                                                    @for($h = 0; $h < 24; $h++)
                                                                        @foreach(['00', '30' ] as $m)
                                                                        @php $time=str_pad($h, 2, '0' , STR_PAD_LEFT) . ':' . $m; @endphp
                                                                        <option value="{{ $time }}"
                                                                        {{ old("working_days.$daySlug.start") == $time ? 'selected' : '' }}>
                                                                        {{ $time }}
                                                                        </option>
                                                                        @endforeach
                                                                        @endfor
                                                                </select>
                                                                {{-- End Time --}}
                                                                <select class="form-control form-control-sm w-auto end-time ml-2 select_end_time"
                                                                    name="working_days[{{ $daySlug }}][end]">
                                                                    @for($h = 0; $h < 24; $h++)
                                                                        @foreach(['00', '30' ] as $m)
                                                                        @php $time=str_pad($h, 2, '0' , STR_PAD_LEFT) . ':' . $m; @endphp
                                                                        <option value="{{ $time }}"
                                                                        {{ old("working_days.$daySlug.end") == $time ? 'selected' : '' }}>
                                                                        {{ $time }}
                                                                        </option>
                                                                        @endforeach
                                                                        @endfor
                                                                </select>
                                                            </div>

                                                            {{-- Service Selection --}}
                                                            <div class="d-flex align-items-center mt-3 w-100">
                                                                <select class="form-control select2_working_days service-select"
                                                                    name="working_days[{{ $daySlug }}][service_1][]" multiple>
                                                                    @forelse($services as $service)
                                                                    <option value="{{ $service->id }}"
                                                                        {{ collect(old("working_days.$daySlug.service_1"))->contains($service->id) ? 'selected' : '' }}>
                                                                        {{ $service->name }}
                                                                    </option>
                                                                    @empty
                                                                    <option value="">No service found</option>
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- TAB: DAYS OFF --}}
                                <div class="tab-pane fade" id="days-off" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 font-weight-bold">Day Offs</h6>
                                                <button type="button" class="btn btn-sm btn-primary" id="addDayOffBtn">
                                                    <i class="feather icon-plus"></i> Add Day Off
                                                </button>
                                            </div>

                                            <div id="dayOffRepeater"></div>

                                            <!-- Template for repeat -->
                                            <template id="dayOffTemplate">
                                                <div class="card border shadow-sm day-off-entry mb-3">
                                                    <div class="card-body position-relative">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-5">
                                                                <label class="font-weight-bold">Day(s) Off <span class="text-danger">*</span></label>
                                                                <select name="day_offs[][week_days][]" class="form-control select2-days" multiple required>
                                                                    @foreach($weekDays as $day)
                                                                    <option value="{{ $day }}">{{ $day }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-5">
                                                                <label class="font-weight-bold">Date Range <span class="text-danger">*</span></label>
                                                                <input type="text" name="day_offs[][date]" class="form-control date-range-picker" placeholder="MMMM D, YYYY - MMMM D, YYYY" required>
                                                            </div>
                                                        </div>

                                                        <button type="button" class="btn btn-sm btn-outline-danger position-absolute" style="top: 10px; right: 10px;" onclick="this.closest('.day-off-entry').remove();">
                                                            <i class="feather icon-trash-2"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary savebutton">Save</button>
                                <a href="{{ route('staff.list') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection