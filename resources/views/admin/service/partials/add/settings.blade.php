<div class="tab-pane" id="settings" role="tabpanel">
    <div class="form-group">
        <label>Default Appointment Status</label>
        <select name="appointment_status" class="form-control select-user">
            @foreach($appointmentStats as $label => $value)
            <option value="{{ $value }}" {{ old('appointment_status') == $value ? 'selected' : '' }}>
                {{ ucfirst($label) }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Minimum Time Required Before Canceling</label>
        <div class="d-flex">
            <select name="cancelling_unit" class="form-control mr-2 select-user mr-3" id="cancelling_unit">
                <option value="hours">Hours</option>
                <option value="days">Days</option>
            </select>
            <select name="cancelling_value" class="form-control select-user" id="cancelling_value">
                <!-- Populated dynamically with JS -->
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Redirect URL After Booking</label>
        <input type="url" name="redirect_url" class="form-control" placeholder="https://example.com" pattern="https?://.*" title="Please enter a valid URL starting with http:// or https://">
    </div>

    <div class="form-group">
        <label>Payment Gateway</label>
        <select name="payment_mode" class="form-control select-user" id="payment_mode">
            <option value="on_site">On Site</option>
            <option value="stripe">Stripe</option>
        </select>
    </div>

    {{-- Stripe Options --}}
    <div class="stripe-options d-none">
        <div class="custom-control custom-radio">
            <input type="radio" id="stripeDefault" name="payment_account" value="default" class="custom-control-input" checked>
            <label class="custom-control-label" for="stripeDefault">Use Default Stripe Account</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="stripeCustom" name="payment_account" value="custom" class="custom-control-input">
            <label class="custom-control-label" for="stripeCustom">Use Different Stripe Account</label>
        </div>

        <div class="stripe-credentials mt-3 d-none">
            <div class="form-group">
                <label class="form-label d-block">Stripe Mode</label>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="payment__is_live" name="payment__is_live" value="1">
                    <label class="custom-control-label" for="payment__is_live">Live Mode</label>
                </div>
            </div>

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