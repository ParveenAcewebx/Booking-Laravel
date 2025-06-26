    @extends('layouts.app')

    @section('content')
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Edit Booking</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="feather icon-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('booking.list') }}">Bookings</a>
                                </li>
                                <li class="breadcrumb-item">Edit Booking</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('booking.update', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-12 order-md-2">
                        <div class="card">
                            <div class="card-header">
                                <h5>Edit Booking</h5>
                            </div>

                            <div class="card-body">
                                {{-- Success or Error Messages --}}
                                @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <h5 class="mb-4">Booking Information</h5>

                                {{-- Rendered Dynamic Fields --}}
                                {!! $dynamicFieldHtml !!}

                                <div class="row">
                                    {{-- Staff Dropdown --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Staff</label>
                                            <select class="form-control" name="staff" required>
                                                <option value="">Select Staff</option>
                                                @foreach($staffList as $staff)
                                                <option value="{{ $staff->id }}"
                                                    {{ old('staff', $booking->selected_staff) == $staff->id ? 'selected' : '' }}>
                                                    {{ $staff->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Booking Date and Time --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Booking Date and Time</label>
                                            <input
                                                type="datetime-local"
                                                class="form-control"
                                                name="booking_datetime"
                                                value="{{ old('booking_datetime', $booking->booking_datetime) }}"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @endsection