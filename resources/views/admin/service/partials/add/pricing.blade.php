<div class="tab-pane active" id="pricing" role="tabpanel">
    <div class="form-group">
        <label>Currency</label>
        <select name="currency" class="form-control select-user">
            @foreach($currencies as $code => $currency)
                <option value="{{ $currency['symbol'] }}">{{ $code }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Price</label>
        <input
            type="text"
            name="price"
            class="form-control"
            value="{{ old('price', $service->price ?? '') }}"
            inputmode="decimal"
            pattern="^\d*\.?\d{0,3}$"
            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/^(\d+(\.\d{0,3})?).*$/, '$1');"
            placeholder="e.g., 100 or 100.50">
    </div>
</div>
