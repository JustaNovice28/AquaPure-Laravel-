<div class="orders-section">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-currency-dollar me-2"></i>Pricing Settings
    </h5>

    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.pricing.update') }}" method="POST">
                        @csrf

                        {{-- Base Price per Gallon --}}
                        <div class="mb-3">
                            <label for="base_price_per_gallon" class="form-label fw-semibold">
                                Base Price per Gallon (Walk-in / Bulk Delivery)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="form-control"
                                    id="base_price_per_gallon"
                                    name="base_price_per_gallon"
                                    value="{{ old('base_price_per_gallon', $settings['base_price_per_gallon'] ?? 25) }}"
                                    required
                                >
                            </div>
                            <div class="form-text">Applied to walk-in orders and delivery orders that meet the bulk threshold.</div>
                        </div>

                        {{-- Delivery Small Order Price --}}
                        <div class="mb-3">
                            <label for="delivery_small_order_price" class="form-label fw-semibold">
                                Delivery Price (Small Orders)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="form-control"
                                    id="delivery_small_order_price"
                                    name="delivery_small_order_price"
                                    value="{{ old('delivery_small_order_price', $settings['delivery_small_order_price'] ?? 30) }}"
                                    required
                                >
                            </div>
                            <div class="form-text">Applied when a delivery order has fewer gallons than the bulk threshold.</div>
                        </div>

                        {{-- Bulk Threshold --}}
                        <div class="mb-4">
                            <label for="delivery_bulk_threshold" class="form-label fw-semibold">
                                Bulk Threshold (Gallons)
                            </label>
                            <input
                                type="number"
                                step="1"
                                min="1"
                                class="form-control"
                                id="delivery_bulk_threshold"
                                name="delivery_bulk_threshold"
                                value="{{ old('delivery_bulk_threshold', $settings['delivery_bulk_threshold'] ?? 5) }}"
                                required
                            >
                            <div class="form-text">Minimum gallons for a delivery order to qualify for the base price.</div>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-save me-1"></i> Save Pricing
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Current Pricing Summary --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-info-circle me-1"></i> Current Pricing Logic
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <span class="badge bg-success me-2">Walk-in</span>
                            Always uses the <strong>base price</strong>.
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-info me-2">Delivery</span>
                            If order is <strong>≥ {{ $settings['delivery_bulk_threshold'] ?? 5 }} gallons</strong> → uses <strong>base price</strong>.<br>
                            If order is <strong>&lt; {{ $settings['delivery_bulk_threshold'] ?? 5 }} gallons</strong> → uses <strong>delivery small order price</strong>.
                        </li>
                    </ul>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Base Price:</span>
                        <strong>₱{{ number_format($settings['base_price_per_gallon'] ?? 25, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Small Delivery Price:</span>
                        <strong>₱{{ number_format($settings['delivery_small_order_price'] ?? 30, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Bulk Threshold:</span>
                        <strong>{{ $settings['delivery_bulk_threshold'] ?? 5 }} gal</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>