<section class="section-padding" id="order">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Place Order</span>
            <h2 class="section-title">Order Water Now</h2>
            <p class="section-subtitle">
                Quick and easy — fill in the form and we'll handle the rest.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-center">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger text-center">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <div class="row g-5 align-items-start">

            {{-- Left Side — Order Type + Summary --}}
            <div class="col-lg-4">

                {{-- Walk-In Card --}}
                <div class="order-type-card mb-3" id="cardWalkIn"
                     onclick="setOrderType('walk-in')">
                    <div class="d-flex align-items-center gap-3">
                        <div class="order-type-icon">
                            <i class="bi bi-droplet-fill"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Walk-In Refill</h6>
                            <small class="text-muted">Bring your gallon to our station</small>
                        </div>
                        <div class="ms-auto text-end">
                            <span class="order-type-price">₱25</span>
                            <small class="text-muted d-block">/gallon</small>
                        </div>
                    </div>
                </div>

                {{-- Delivery Card --}}
                <div class="order-type-card mb-3 active-order-type" id="cardDelivery"
                     onclick="setOrderType('delivery')">
                    <div class="d-flex align-items-center gap-3">
                        <div class="order-type-icon delivery-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Home Delivery</h6>
                            <small class="text-muted">We deliver to your door</small>
                        </div>
                        <div class="ms-auto text-end">
                            <span class="order-type-price">₱30</span>
                            <small class="text-muted d-block">/gallon</small>
                        </div>
                    </div>
                </div>

                {{-- Suki Promo --}}
                <div class="suki-promo-card">
                    <i class="bi bi-star-fill text-warning"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Suki Promo!</h6>
                        <p class="mb-0" style="font-size:0.85rem">
                            Order <strong>5 or more gallons</strong> and get
                            delivery at <strong>walk-in price (₱25/gallon)</strong>!
                        </p>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="order-summary-card mt-3">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-receipt me-2"></i>Order Summary
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Type:</span>
                        <span class="fw-semibold" id="summaryType">Delivery</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Gallons:</span>
                        <span class="fw-semibold" id="summaryGallons">1</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Price/gallon:</span>
                        <span class="fw-semibold" id="summaryPPG">₱30</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 d-none" id="summaryDateRow">
                        <span class="text-muted">Date:</span>
                        <span class="fw-semibold" id="summaryDate" style="font-size:0.85rem"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 d-none" id="summaryTimeRow">
                        <span class="text-muted">Time:</span>
                        <span class="fw-semibold" id="summaryTime" style="font-size:0.85rem"></span>
                    </div>
                    <div class="suki-discount-badge mb-2 d-none" id="sukiNote">
                        <i class="bi bi-tag-fill me-1"></i>Suki discount applied! (FREE delivery)
                    </div>
                    <small class="text-muted d-block mb-2" id="sukiHint"></small>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total:</span>
                        <span class="fw-bold text-primary" style="font-size:1.3rem" id="summaryTotal">
                            ₱30.00
                        </span>
                    </div>
                </div>
            </div>

            {{-- Right Side — Form --}}
            <div class="col-lg-8">
                <div class="contact-form-box">
                    <h4 class="mb-4 fw-bold">
                        <i class="bi bi-cart-fill text-primary me-2"></i>
                        <span id="formTitle">Delivery Order</span>
                    </h4>

                    <form action="{{ route('order.store') }}" method="POST" id="orderForm">
                        @csrf
                        {{-- Hidden order type --}}
                        <input type="hidden" name="order_type" id="orderTypeInput" value="delivery">

                        <div class="row g-3">

                            {{-- Delivery Fields --}}
                            <div id="deliveryFields">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Full Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control"
                                               name="customer_name"
                                               placeholder="e.g. Juan Dela Cruz"
                                               value="{{ old('customer_name') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Phone Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control"
                                               name="phone"
                                               placeholder="09XX-XXX-XXXX"
                                               value="{{ old('phone') }}">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            Delivery Address <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" name="address" rows="2"
                                                  placeholder="e.g. Purok 5, Barra, Opol near sari-sari store ni Aling Nena">{{ old('address') }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Preferred Delivery Date <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control"
                                               name="delivery_date"
                                               id="deliveryDate"
                                               value="{{ old('delivery_date') }}">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            You can book up to 7 days in advance
                                        </small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Preferred Delivery Time <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" name="delivery_time" id="deliveryTime">
                                            <option value="" disabled selected>Select delivery time</option>
                                            @foreach([
                                                '6:00 AM - 8:00 AM'   => '🌅 6:00 AM - 8:00 AM (Early Morning)',
                                                '8:00 AM - 10:00 AM'  => '☀️ 8:00 AM - 10:00 AM (Morning)',
                                                '10:00 AM - 12:00 PM' => '☀️ 10:00 AM - 12:00 PM (Late Morning)',
                                                '12:00 PM - 2:00 PM'  => '🌤️ 12:00 PM - 2:00 PM (Afternoon)',
                                                '2:00 PM - 4:00 PM'   => '🌤️ 2:00 PM - 4:00 PM (Mid Afternoon)',
                                                '4:00 PM - 6:00 PM'   => '🌇 4:00 PM - 6:00 PM (Late Afternoon)',
                                                '6:00 PM - 8:00 PM'   => '🌙 6:00 PM - 8:00 PM (Evening)',
                                            ] as $val => $label)
                                            <option value="{{ $val }}" {{ old('delivery_time') === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            We operate Mon–Sat 6AM–8PM, Sun 7AM–5PM
                                        </small>
                                    </div>

                                    {{-- Sunday Warning --}}
                                    <div class="col-12 d-none" id="sundayWarning">
                                        <div class="alert alert-warning mb-0 py-2">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                            <strong>Sunday selected!</strong> Delivery hours are limited
                                            to <strong>7:00 AM – 5:00 PM</strong> only.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Walk-In Notice --}}
                            <div class="col-12 d-none" id="walkInNotice">
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <strong>Walk-In:</strong> Bring your gallon container to our station at
                                    <strong>Barra, Opol, Misamis Oriental</strong>.<br>
                                    Open Mon–Sat 6AM–8PM, Sun 7AM–5PM.
                                </div>
                            </div>

                            {{-- Gallon Counter --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Number of Gallons <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-primary"
                                            onclick="changeGallons(-1)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" class="form-control text-center fw-bold"
                                           name="gallons" id="gallonsInput"
                                           min="1" max="50" value="1"
                                           style="font-size:1.2rem"
                                           oninput="updateSummary()">
                                    <button type="button" class="btn btn-outline-primary"
                                            onclick="changeGallons(1)">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Mobile Total --}}
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="w-100 p-3 bg-light rounded text-center">
                                    <small class="text-muted d-block">Total</small>
                                    <span class="fw-bold text-primary" style="font-size:1.5rem"
                                          id="mobileTotal">₱30.00</span>
                                    <small class="text-success d-block fw-semibold d-none"
                                           id="freeDeliveryNote">✓ FREE delivery applied!</small>
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Notes <small class="text-muted">(optional)</small>
                                </label>
                                <textarea class="form-control" name="notes" rows="2"
                                          placeholder="e.g. Please deliver in the morning, gate is blue...">{{ old('notes') }}</textarea>
                            </div>

                            {{-- Submit --}}
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-custom w-100 py-3">
                                    <i class="bi bi-cart-check-fill me-2"></i>
                                    Place Order — <span id="btnTotal">₱30.00</span>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    let orderType = 'delivery';
    let gallons   = 1;

    // ── Set min/max dates ────────────────────────────────────────────
    (function () {
        const dateInput = document.getElementById('deliveryDate');
        if (!dateInput) return;
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const maxDate = new Date();
        maxDate.setDate(maxDate.getDate() + 7);
        dateInput.min = tomorrow.toISOString().split('T')[0];
        dateInput.max = maxDate.toISOString().split('T')[0];
        dateInput.addEventListener('change', () => {
            const day = new Date(dateInput.value).getDay();
            document.getElementById('sundayWarning')
                    .classList.toggle('d-none', day !== 0);
            const formatted = dateInput.value
                ? new Date(dateInput.value).toLocaleDateString('en-US',
                    { month: 'short', day: 'numeric', year: 'numeric' })
                : '';
            document.getElementById('summaryDate').textContent = formatted;
            document.getElementById('summaryDateRow').classList.toggle('d-none', !dateInput.value);
        });

        document.getElementById('deliveryTime').addEventListener('change', function () {
            document.getElementById('summaryTime').textContent = this.value;
            document.getElementById('summaryTimeRow').classList.toggle('d-none', !this.value);
        });
    })();

    // ── Order type toggle ────────────────────────────────────────────
    function setOrderType(type) {
        orderType = type;
        document.getElementById('orderTypeInput').value = type;

        document.getElementById('cardWalkIn')
                .classList.toggle('active-order-type', type === 'walk-in');
        document.getElementById('cardDelivery')
                .classList.toggle('active-order-type', type === 'delivery');

        document.getElementById('deliveryFields')
                .classList.toggle('d-none', type === 'walk-in');
        document.getElementById('walkInNotice')
                .classList.toggle('d-none', type !== 'walk-in');

        document.getElementById('formTitle').textContent =
            type === 'walk-in' ? 'Walk-In Refill Order' : 'Delivery Order';

        // Reset delivery-only summary rows when switching to walk-in
        if (type === 'walk-in') {
            document.getElementById('summaryDateRow').classList.add('d-none');
            document.getElementById('summaryTimeRow').classList.add('d-none');
            document.getElementById('sundayWarning').classList.add('d-none');
        }

        updateSummary();
    }

    // ── Gallon counter ───────────────────────────────────────────────
    function changeGallons(delta) {
        const input = document.getElementById('gallonsInput');
        gallons = Math.min(50, Math.max(1, (parseInt(input.value) || 1) + delta));
        input.value = gallons;
        updateSummary();
    }

    // ── Price calculation ────────────────────────────────────────────
    function calculatePrice() {
        gallons = parseInt(document.getElementById('gallonsInput').value) || 1;
        if (orderType === 'walk-in') return { ppg: 25, total: gallons * 25 };
        if (gallons >= 5)            return { ppg: 25, total: gallons * 25 };
        return { ppg: 30, total: gallons * 30 };
    }

    // ── Update all summary elements ──────────────────────────────────
    function updateSummary() {
        gallons = parseInt(document.getElementById('gallonsInput').value) || 1;
        const { ppg, total } = calculatePrice();
        const totalStr = '₱' + total.toFixed(2);
        const isSuki   = orderType === 'delivery' && gallons >= 5;
        const hint     = orderType === 'delivery' && gallons < 5
            ? `💡 Add ${5 - gallons} more gallon${5 - gallons > 1 ? 's' : ''} for FREE delivery!`
            : '';

        document.getElementById('summaryType').textContent =
            orderType === 'walk-in' ? 'Walk-In' : 'Delivery';
        document.getElementById('summaryGallons').textContent = gallons;
        document.getElementById('summaryPPG').textContent     = '₱' + ppg;
        document.getElementById('summaryTotal').textContent   = totalStr;
        document.getElementById('mobileTotal').textContent    = totalStr;
        document.getElementById('btnTotal').textContent       = totalStr;
        document.getElementById('sukiNote').classList.toggle('d-none', !isSuki);
        document.getElementById('sukiHint').textContent       = hint;
        document.getElementById('freeDeliveryNote')
                .classList.toggle('d-none', !isSuki);
    }

    // Init
    updateSummary();
</script>
@endpush