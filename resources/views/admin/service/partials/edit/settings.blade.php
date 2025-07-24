<div class="tab-pane" id="settings" role="tabpanel">
    <div class="form-group">
        <label>Default Appointment Status</label>
        <select name="appointment_status" class="form-control select-user">
            @foreach($appointmentStats as $label => $value)
            <option value="{{ $value }}" {{ $service->appointment_status == $value ? 'selected' : '' }}>{{ ucfirst($label) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Minimum Time Required Before Canceling</label>
        <div class="d-flex">
            <div class="col-md-6 p-0">
                <select name="cancelling_unit" class="form-control mr-2 select-user" id="cancelling_unit">
                    <option value="hours" {{ $service->cancelling_unit == 'hours' ? 'selected' : '' }}>Hours</option>
                    <option value="days" {{ $service->cancelling_unit == 'days' ? 'selected' : '' }}>Days</option>
                </select>
            </div>
            <div class="col-md-6 pr-0">
                <select name="cancelling_value" class="form-control select-user" id="cancelling_value">
                    <!-- Options populated by JS -->
                </select>
                <input type="hidden" id="cancel_value" value="{{ $service->cancelling_value }}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Redirect URL After Booking</label>
        <input type="url" name="redirect_url" class="form-control" value="{{ $service->redirect_url }}" placeholder="https://example.com" pattern="https?://.*" title="Please enter a valid URL starting with http:// or https://">
    </div>
    <div class="form-group">
        <label>Payment Mode</label>
        <select name="payment_mode" class="form-control select-user" id="payment_mode">
            <option value="on_site" {{ $service->payment_mode == 'on_site' ? 'selected' : '' }}>On Site</option>
            <option value="stripe" {{ $service->payment_mode == 'stripe' ? 'selected' : '' }}>Stripe</option>
        </select>
    </div>

    {{-- Stripe Options --}}
    <div class="stripe-options {{ $service->payment_mode == 'stripe' ? '' : 'd-none' }}">
        {{-- Stripe Account Type Radios --}}
        <div class="custom-control custom-radio">
            <input type="radio" id="stripeDefault" name="payment_account" value="default"
                class="custom-control-input"
                {{ $service->payment_account == 'default' ? 'checked' : '' }}>
            <label class="custom-control-label" for="stripeDefault">Use Default Stripe Account</label>
        </div>

        <div class="custom-control custom-radio">
            <input type="radio" id="stripeCustom" name="payment_account" value="custom"
                class="custom-control-input"
                {{ $service->payment_account == 'custom' ? 'checked' : '' }}>
            <label class="custom-control-label" for="stripeCustom">Use Different Stripe Account</label>
        </div>

        {{-- Stripe Credentials Section --}}
        <div class="stripe-credentials mt-3 {{ $service->payment_account == 'custom' ? '' : 'd-none' }}">
            {{-- Stripe Mode Toggle (Live/Test) --}}
            <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="payment__is_live"
                    name="payment__is_live" value="1"
                    {{ $service->payment__is_live ? 'checked' : '' }}>
                <label class="custom-control-label" for="payment__is_live">Live Mode</label>
            </div>

            {{-- Test Mode Keys --}}
            <div class="stripe-test {{ $service->payment__is_live ? 'd-none' : '' }}">
                <div class="form-group">
                    <label for="stripe_test_site_key">Test Site Key</label>
                    <input type="text" name="stripe_test_site_key" id="stripe_test_site_key"
                        class="form-control" value="{{ $service->stripe_test_site_key }}">
                </div>
                <div class="form-group">
                    <label for="stripe_test_secret_key">Test Secret Key</label>
                    <input type="text" name="stripe_test_secret_key" id="stripe_test_secret_key"
                        class="form-control" value="{{ $service->stripe_test_secret_key }}">
                </div>
            </div>

            {{-- Live Mode Keys --}}
            <div class="stripe-live {{ $service->payment__is_live ? '' : 'd-none' }}">
                <div class="form-group">
                    <label for="stripe_live_site_key">Live Site Key</label>
                    <input type="text" name="stripe_live_site_key" id="stripe_live_site_key"
                        class="form-control" value="{{ $service->stripe_live_site_key }}">
                </div>
                <div class="form-group">
                    <label for="stripe_live_secret_key">Live Secret Key</label>
                    <input type="text" name="stripe_live_secret_key" id="stripe_live_secret_key"
                        class="form-control" value="{{ $service->stripe_live_secret_key }}">
                </div>
            </div>
        </div>
    </div>
</div>