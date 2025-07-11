<div class="tab-pane fade show active" id="user-details" role="tabpanel">
    <div class="row">

        {{-- Avatar --}}
        <div class="col-md-12 mb-3 text-center">
            <img src="{{ $staff->avatar ? asset('storage/' . $staff->avatar) : asset('assets/images/no-image-available.png') }}" class="img-radius mb-3 wid-80 hei-80" alt="User Avatar">
            <div class="custom-file mx-auto">
                <input type="file" class="custom-file-input" name="avatar" id="avatar">
                <label class="custom-file-label" for="avatar">Choose file...</label>
            </div>
        </div>

        {{-- Name --}}
        <div class="col-md-6">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $staff->name) }}" required>
            </div>
        </div>

        {{-- Email --}}
        <div class="col-md-6">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $staff->email) }}" required>
            </div>
        </div>

        {{-- Password --}}
        <div class="col-md-6">
            <div class="form-group">
                <label>Password:</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="col-md-6">
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                <div id="password-error" class="text-danger mt-1 d-none">Passwords do not match.</div>
            </div>
        </div>

        {{-- Phone Number --}}
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <div class="input-group">
                    <select class="form-control" name="code" style="max-width: 100px;">
                        @foreach($phoneCountries as $country)
                        <option value="{{ $country['code'] }}"
                            @if((!old('phone_number', $staff->phone_number ?? null) && $country['code'] == '+91') ||
                            (old('phone_number', $staff->phone_number ?? null) && Str::startsWith(old('phone_number', $staff->phone_number ?? null), $country['code'])))
                            selected
                            @endif>
                            {{ $country['code'] }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" class="form-control" name="phone_number"
                        value="{{ old('phone_number', $staff->phone_number ?? '') }}" required>
                </div>
                @error('phone_number')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @if($roles)
        <div class="col-md-6 d-none">
            <div class="form-group">
                <label>Role:</label>
                <select class="form-control select_role" name="role" required>
                    <option value="{{ $roles->id }}" selected>{{ $roles->name }}</option>
                </select>
            </div>
        </div>
        @endif
        {{-- Status --}}
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label d-block">Status</label>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="status" id="status"
                        value="{{ config('constants.status.active') }}"
                        {{ $staff->status == config('constants.status.active') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="status">Active</label>
                </div>
            </div>
        </div>

    </div>
</div>