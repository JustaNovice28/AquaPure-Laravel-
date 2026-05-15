<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Order Receipt #{{ $order->id }} — AquaPure</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"/>
    @vite(['resources/css/app.css', 'resources/css/styles/receipt.css'])

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: rgba(0, 0, 0, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="receipt-wrapper position-relative" id="receipt-printable">
        <div class="receipt-card">

            {{-- Close Button --}}
            <a href="{{ route('home') }}#order"
               class="receipt-close-btn"
               aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </a>

            {{-- Header --}}
            <div class="receipt-header text-center">
                <div class="receipt-logo">
                    <i class="bi bi-droplet-fill"></i>
                </div>
                <h4 class="fw-bold">AquaPure Water Refilling</h4>
                <p class="text-muted mb-0">Barra, Opol, Misamis Oriental</p>
                <hr>
                <h5 class="fw-bold">ORDER RECEIPT</h5>
                <p class="fw-bold text-primary">Order #{{ $order->id }}</p>
            </div>

            {{-- Order Details --}}
            <table class="table table-borderless receipt-table">
                <tbody>
                    <tr>
                        <td class="fw-semibold">Customer:</td>
                        <td class="text-end">{{ $order->customer_name ?: '—' }}</td>
                    </tr>

                    @if($order->phone)
                    <tr>
                        <td class="fw-semibold">Phone:</td>
                        <td class="text-end">
                            <span id="phoneDisplay">
                                {{ strlen($order->phone) >= 7
                                    ? substr($order->phone, 0, 3) . '****' . substr($order->phone, -4)
                                    : $order->phone }}
                            </span>
                            <i class="bi bi-eye ms-2"
                               id="phoneToggle"
                               data-full="{{ $order->phone }}"
                               data-masked="{{ strlen($order->phone) >= 7
                                    ? substr($order->phone, 0, 3) . '****' . substr($order->phone, -4)
                                    : $order->phone }}"
                               style="cursor:pointer; font-size:0.9rem; color:#0d6efd"
                               onclick="toggleField('phone')"
                               title="Show full number">
                            </i>
                        </td>
                    </tr>
                    @endif

                    @if($order->address)
                    <tr>
                        <td class="fw-semibold">Address:</td>
                        <td class="text-end">
                            <span id="addressDisplay">
                                {{ strlen($order->address) > 30
                                    ? substr($order->address, 0, 25) . '…'
                                    : $order->address }}
                            </span>
                            @if(strlen($order->address) > 30)
                            <i class="bi bi-eye ms-2"
                               id="addressToggle"
                               data-full="{{ $order->address }}"
                               data-masked="{{ substr($order->address, 0, 25) . '…' }}"
                               style="cursor:pointer; font-size:0.9rem; color:#0d6efd"
                               onclick="toggleField('address')"
                               title="Show full address">
                            </i>
                            @endif
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td class="fw-semibold">Order Type:</td>
                        <td class="text-end">
                            {{ $order->order_type === 'walk-in' ? '🚶 Walk-In' : '🚚 Delivery' }}
                        </td>
                    </tr>

                    @if($order->order_type !== 'walk-in' && $order->delivery_date)
                    <tr>
                        <td class="fw-semibold">Delivery Date:</td>
                        <td class="text-end">
                            {{ \Carbon\Carbon::parse($order->delivery_date)->format('M j, Y') }}
                        </td>
                    </tr>
                    @endif

                    @if($order->order_type !== 'walk-in' && $order->delivery_time)
                    <tr>
                        <td class="fw-semibold">Time Slot:</td>
                        <td class="text-end">{{ $order->delivery_time }}</td>
                    </tr>
                    @endif

                    <tr>
                        <td class="fw-semibold">Gallons:</td>
                        <td class="text-end">{{ $order->gallons }} L</td>
                    </tr>

                    @if($order->notes)
                    <tr>
                        <td class="fw-semibold">Notes:</td>
                        <td class="text-end">{{ $order->notes }}</td>
                    </tr>
                    @endif

                    <tr>
                        <td class="fw-semibold">Status:</td>
                        <td class="text-end">
                            @php
                                $badge = match($order->status) {
                                    'pending'   => 'warning',
                                    'confirmed' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    default     => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ $order->status }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>

            {{-- Total --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold fs-5">Total:</span>
                <span class="fw-bold fs-4 text-primary">
                    ₱{{ number_format($order->total_price, 2) }}
                </span>
            </div>

            {{-- Actions --}}
            <div class="text-center receipt-footer">
                <button class="btn btn-primary" onclick="handlePrint()">
                    <i class="bi bi-download me-1"></i> Download / Print
                </button>
                <p class="text-muted mt-3 small">Thank you for choosing AquaPure! 🙏</p>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Toggle phone/address ─────────────────────────────────────
        const fieldState = { phone: false, address: false };
        function toggleField(field) {
            fieldState[field] = !fieldState[field];
            const toggle  = document.getElementById(field + 'Toggle');
            const display = document.getElementById(field + 'Display');
            if (!toggle || !display) return;
            display.textContent = fieldState[field] ? toggle.dataset.full : toggle.dataset.masked;
            toggle.className = fieldState[field] ? 'bi bi-eye-slash ms-2' : 'bi bi-eye ms-2';
        }

        // ── Print ────────────────────────────────────────────────────
        function handlePrint() {
            const statusColors = {
                pending:   { color: '#856404', bg: '#fff3cd' },
                confirmed: { color: '#0c5460', bg: '#d1ecf1' },
                completed: { color: '#155724', bg: '#d4edda' },
                cancelled: { color: '#721c24', bg: '#f8d7da' },
            };
            const status = '{{ $order->status }}';
            const badge  = statusColors[status] || { color: '#383d41', bg: '#e2e3e5' };
            const row = (label, value) =>
                value ? `<tr><td><strong>${label}</strong></td><td>${value}</td></tr>` : '';
            const orderType = '{{ $order->order_type }}';
            const win = window.open('', '_blank', 'width=620,height=820');
            win.document.write(`
                <!DOCTYPE html><html>
                <head>
                    <meta charset="UTF-8"/>
                    <title>Receipt — Order #{{ $order->id }}</title>
                    <style>
                        * { box-sizing:border-box; margin:0; padding:0; }
                        body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
                               font-size:14px; color:#212529; padding:2cm; background:white; }
                        .header { text-align:center; margin-bottom:1.5rem; }
                        .logo { font-size:2.5rem; margin-bottom:.5rem; }
                        .header h1 { font-size:1.4rem; font-weight:700; }
                        .header p  { font-size:.875rem; color:#6c757d; }
                        hr { border:none; border-top:1px solid #dee2e6; margin:1rem 0; }
                        .receipt-title { text-align:center; font-size:1.1rem; font-weight:700; }
                        .order-id { text-align:center; font-weight:700; color:#0d6efd; margin-bottom:1rem; }
                        table { width:100%; border-collapse:collapse; }
                        td { padding:7px 0; }
                        td:last-child { text-align:right; }
                        .badge { display:inline-block; padding:3px 10px; border-radius:6px;
                                 font-size:.8rem; font-weight:600;
                                 background:${badge.bg}; color:${badge.color}; }
                        .total-row { display:flex; justify-content:space-between;
                                     margin-top:1rem; padding-top:.75rem;
                                     border-top:2px solid #dee2e6; }
                        .total-amount { font-size:1.4rem; font-weight:700; color:#0d6efd; }
                        .footer { text-align:center; margin-top:1.5rem; font-size:.85rem; color:#6c757d; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="logo">💧</div>
                        <h1>AquaPure Water Refilling</h1>
                        <p>Barra, Opol, Misamis Oriental</p>
                    </div>
                    <hr/>
                    <div class="receipt-title">ORDER RECEIPT</div>
                    <div class="order-id">Order #{{ $order->id }}</div>
                    <table>
                        ${row('Customer:', '{{ $order->customer_name ?: "—" }}')}
                        ${row('Phone:', '{{ $order->phone ?: "—" }}')}
                        ${row('Address:', '{{ $order->address }}')}
                        <tr><td><strong>Order Type:</strong></td>
                            <td>${orderType === 'walk-in' ? '🚶 Walk‑In' : '🚚 Delivery'}</td></tr>
                        ${orderType !== 'walk-in' ? row('Delivery Date:', '{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format("M j, Y") : "" }}') : ''}
                        ${orderType !== 'walk-in' ? row('Time Slot:', '{{ $order->delivery_time }}') : ''}
                        ${row('Gallons:', '{{ $order->gallons }} L')}
                        ${row('Notes:', '{{ $order->notes }}')}
                        <tr><td><strong>Status:</strong></td>
                            <td><span class="badge">${status}</span></td></tr>
                    </table>
                    <div class="total-row">
                        <span style="font-size:1.1rem;font-weight:700">Total:</span>
                        <span class="total-amount">&#8369;{{ number_format($order->total_price, 2) }}</span>
                    </div>
                    <div class="footer">Thank you for choosing AquaPure! 🙏</div>
                </body></html>
            `);
            win.document.close();
            win.onload = () => { win.focus(); win.print(); win.onafterprint = () => win.close(); };
        }
    </script>
</body>
</html>