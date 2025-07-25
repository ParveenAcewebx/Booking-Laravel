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
                            <li class="breadcrumb-item"><a href="#!">Vendor</a></li>
                            <li class="breadcrumb-item"><a href="#!">Add Vendor</a></li>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Name" required>
                                        @error('username')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                        @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <div id="quill-editor" style="height: 200px;"></div>
                                        <textarea name="description" id="description" class="d-none"></textarea>
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
                                        {{ old('status', 1) == config('constants.status.active') ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="{{ config('constants.status.inactive') }}"
                                        {{ old('status', 1) == config('constants.status.inactive') ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Featured Image -->
                            <div class="form-group">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="thumbnail" id="addAvatarInput"
                                            accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        <label class="custom-file-label overflow-hidden" for="addAvatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Supported image types: JPG, JPEG, PNG, or GIF.
                                </small>
                            </div>

                            <!-- Preview -->
                            <div id="add-avatar-preview-container" class="row d-none mt-3">
                                <div class="col-md-6 position-relative">
                                    <div class="card shadow-sm">
                                        <img id="add-avatar-preview" class="card-img-top img-thumbnail" alt="Avatar Preview">

                                        <button type="button"
                                            id="remove-add-avatar-preview"
                                            class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                            title="Remove image">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('avatar')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror

                            <!-- Submit Button -->
                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stripe Account Tab -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card mt-3">
                        <div class="card-body">
                            <!-- Nav Tabs -->
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#stripeAccount" role="tab">
                                        <i class="fab fa-stripe"></i> Stripe
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="stripeAccount" role="tabpanel">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="payment__is_live" name="stripe_mode" value="1">
                                            <label class="custom-control-label" for="payment__is_live">Live Mode</label>
                                        </div>
                                    </div>

                                    <!-- Test Keys -->
                                    <div class="stripe-test">
                                        <div class="form-group">
                                            <label>Test Site Key</label>
                                            <input type="text" name="stripe_test_site_key" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Test Secret Key</label>
                                            <input type="text" name="stripe_test_secret_key" class="form-control">
                                        </div>
                                    </div>

                                    <!-- Live Keys -->
                                    <div class="stripe-live d-none">
                                        <div class="form-group">
                                            <label>Live Site Key</label>
                                            <input type="text" name="stripe_live_site_key" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Live Secret Key</label>
                                            <input type="text" name="stripe_live_secret_key" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- [ Main Content ] end -->

    </div>
</section>
@endsection