<div class="tab-pane fade show active" id="stripeAccount" role="tabpanel">
                                        <div class="stripe-credentialss mt-3">
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="payment__is_live"
                                                    name="stripe_mode" value="1"
                                                    {{ $vendor->stripe_mode == 1 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="payment__is_live">Live Mode</label>
                                            </div>
                                            <div class="stripe-test {{ $vendor->stripe_mode ? 'd-none' : '' }}">
                                                <div class="form-group">
                                                    <label for="stripe_test_site_key">Test Site Key</label>
                                                    <input type="text" name="stripe_test_site_key" id="stripe_test_site_key"
                                                        class="form-control"
                                                        value="{{ old('stripe_test_site_key', $vendor->stripe_test_site_key) }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="stripe_test_secret_key">Test Secret Key</label>
                                                    <input type="text" name="stripe_test_secret_key" id="stripe_test_secret_key"
                                                        class="form-control"
                                                        value="{{ old('stripe_test_secret_key', $vendor->stripe_test_secret_key) }}">
                                                </div>
                                            </div>
                                            <div class="stripe-live {{ $vendor->stripe_mode ? '' : 'd-none' }}">
                                                <div class="form-group">
                                                    <label for="stripe_live_site_key">Live Site Key</label>
                                                    <input type="text" name="stripe_live_site_key" id="stripe_live_site_key"
                                                        class="form-control"
                                                        value="{{ old('stripe_live_site_key', $vendor->stripe_live_site_key) }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="stripe_live_secret_key">Live Secret Key</label>
                                                    <input type="text" name="stripe_live_secret_key" id="stripe_live_secret_key"
                                                        class="form-control"
                                                        value="{{ old('stripe_live_secret_key', $vendor->stripe_live_secret_key) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>