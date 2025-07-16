<div class="col-md-12 mb-3 text-center">
    <img src="{{ asset('assets/images/no-image-available.png') }}" class="img-radius mb-3" width="100" alt="User Avatar">
    <div class="custom-file mx-auto">
        <input type="file" class="custom-file-input" name="avatar" id="avatar">
        <label class="custom-file-label" for="avatar">Choose file...</label>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Name:</label>
        <input type="text" class="form-control" name="name" required>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Email:</label>
        <input type="email" class="form-control" name="email" required>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Password:</label>
        <input type="password" class="form-control" name="password" required>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Confirm Password:</label>
        <input type="password" class="form-control" name="password_confirmation" required>
        <div id="password-error"></div>
    </div>
</div>

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

@if($roles)
<div class="col-md-6 d-none">
    <div class="form-group">
        <label>Role:</label>
        <select class="form-control select-user" name="role" required>
            <option value="{{ $roles->id }}" selected>{{ $roles->name }}</option>
        </select>
    </div>
</div>
@endif

<div class="col-md-6">
    <div class="form-group">
        <label for="status" class="form-label d-block">Status</label>
        <select name="status" id="status" class="form-control select-user">
            <option value="{{ config('constants.status.active') }}"
                {{ old('status') == config('constants.status.active') ? 'selected' : '' }}>
                Active
            </option>
            <option value="{{ config('constants.status.inactive') }}"
                {{ old('status') == config('constants.status.inactive') ? 'selected' : '' }}>
                Inactive
            </option>
        </select>
        @error('status')
        <div class="error text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>