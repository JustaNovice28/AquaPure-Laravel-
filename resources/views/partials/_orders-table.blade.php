{{--
    _orders-table.blade.php
    Drop this into resources/views/admin/partials/_orders-table.blade.php
    and include it in dashboard.blade.php with:
        @include('admin.partials._orders-table')

    Expects: $orders (Collection), $filterStatus (string), $filterType (string)
    Both $filterStatus and $filterType come from request() — set defaults at top of dashboard.
--}}

@php
    $filterStatus = request('status', 'all');
    $filterType   = request('type', 'all');

    $filtered = $orders
        ->when($filterStatus !== 'all', fn($c) => $c->where('status', $filterStatus))
        ->when($filterType   !== 'all', fn($c) => $c->where('order_type', $filterType));
@endphp

<div class="orders-section">

    {{-- ── Header row: count + status filter buttons ── --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-list-ul me-2"></i>Recent Orders ({{ $filtered->count() }})
        </h5>
        <div class="btn-group">
            @foreach(['all','pending','confirmed','completed','cancelled'] as $s)
                <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['tab'=>'orders','status'=>$s])) }}"
                   class="btn btn-sm {{ $filterStatus === $s ? 'btn-primary' : 'btn-outline-secondary' }}">
                    {{ ucfirst($s) }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="admin-table-container">
        <div class="table-responsive">
            <table class="table table-hover admin-table">
                <thead>
                    <tr class="table-header">
                        <th>#</th>
                        <th>CUSTOMER</th>

                        {{-- TYPE column header — acts as a dropdown filter --}}
                        <th style="position:relative;">
                            <span
                                id="typeFilterToggle"
                                style="cursor:pointer; user-select:none; display:inline-flex; align-items:center; gap:4px;"
                                onclick="document.getElementById('typeDropdown').classList.toggle('show')"
                            >
                                TYPE
                                @if($filterType !== 'all')
                                    : {{ $filterType === 'walk-in' ? 'Walk‑In' : 'Delivery' }}
                                @endif
                                <i class="bi bi-chevron-down" style="font-size:.65em; opacity:.7;"></i>
                            </span>

                            <ul id="typeDropdown" class="dropdown-menu" style="position:absolute;top:100%;left:0;margin-top:4px;min-width:150px;z-index:1000;">
                                @foreach([
                                    'all'      => 'All Types',
                                    'walk-in'  => '🚶 Walk‑In',
                                    'delivery' => '🚚 Delivery',
                                ] as $val => $label)
                                    <li>
                                        <a class="dropdown-item {{ $filterType === $val ? 'active' : '' }}"
                                           href="{{ route('admin.dashboard', array_merge(request()->query(), ['tab'=>'orders','type'=>$val])) }}">
                                            {{ $label }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </th>

                        <th>GALLONS</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th>DELIVERY / ORDER DATE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($filtered as $order)
                        @php
                            $typeIcon  = $order->order_type === 'walk-in' ? '🚶' : '🚚';
                            $typeLabel = $order->order_type === 'walk-in' ? 'Walk-In' : 'Delivery';
                            $typeBadge = $order->order_type === 'walk-in' ? 'bg-info' : 'bg-success';

                            $statusColors = [
                                'pending'   => 'warning',
                                'confirmed' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                            ];
                            $statusLabels = [
                                'pending'   => '⏳ Pending',
                                'confirmed' => '✓ Confirmed',
                                'completed' => '✅ Completed',
                                'cancelled' => '✗ Cancelled',
                            ];
                            $statusColor = $statusColors[$order->status] ?? 'secondary';
                            $statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status);

                            // Date cell: delivery date for delivery orders, created_at for walk-in
                            if ($order->order_type === 'delivery' && $order->delivery_date) {
                                $dateCell = '📅 ' . \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y')
                                          . ($order->delivery_time ? ' (' . $order->delivery_time . ')' : '');
                            } else {
                                $dateCell = '🛒 ' . $order->created_at->format('M d, Y g:i A');
                            }
                        @endphp

                        {{-- ── Main row ── --}}
                        <tr class="table-row">
                            <td class="fw-bold">#{{ $order->id }}</td>

                            <td>
                                <p class="mb-0 fw-semibold">{{ $order->customer_name ?: '—' }}</p>
                                <small class="text-muted">{{ $order->phone }}</small>
                            </td>

                            <td>
                                <span class="badge {{ $typeBadge }}">{{ $typeIcon }} {{ $typeLabel }}</span>
                            </td>

                            <td class="fw-semibold">{{ $order->gallons }}L</td>

                            <td class="fw-bold text-primary">₱{{ number_format($order->total_price, 2) }}</td>

                            {{-- Status — Bootstrap dropdown, each option is a tiny POST form --}}
                            <td>
                                <div class="dropdown">
                                    <button
                                        class="btn btn-sm btn-{{ $statusColor }} dropdown-toggle status-btn"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        data-bs-auto-close="true"
                                        aria-expanded="false"
                                    >
                                        {{ $statusLabel }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach(['pending'=>'⏳ Pending','confirmed'=>'✓ Confirmed','completed'=>'✅ Completed'] as $s => $l)
                                            <li>
                                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="{{ $s }}">
                                                    <button type="submit" class="dropdown-item {{ $order->status === $s ? 'active' : '' }}">
                                                        {{ $l }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="dropdown-item text-danger">
                                                    ✗ Cancelled
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>

                            <td class="text-muted small">{{ $dateCell }}</td>

                            <td>
                                <button
                                    class="btn btn-sm btn-outline-secondary"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#order-detail-{{ $order->id }}"
                                    aria-expanded="false"
                                >
                                    Details
                                </button>
                            </td>
                        </tr>

                        {{-- ── Expanded detail row ── --}}
                        <tr class="collapse-row">
                            <td colspan="8" class="p-0">
                                <div class="collapse" id="order-detail-{{ $order->id }}">
                                    <div class="order-details-expanded p-3 bg-light">
                                        <div class="row g-3">

                                            {{-- Customer --}}
                                            <div class="col-md-6">
                                                <h6 class="fw-bold"><i class="bi bi-person-fill me-2"></i>Customer</h6>
                                                <p class="mb-1"><strong>{{ $order->customer_name ?: '—' }}</strong></p>
                                                <p class="mb-1">📞 {{ $order->phone ?: '—' }}</p>
                                                @if($order->address)
                                                    <p class="mb-0">📍 {{ $order->address }}</p>
                                                @endif
                                            </div>

                                            {{-- Order info --}}
                                            <div class="col-md-6">
                                                <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Order Info</h6>
                                                <p class="mb-1"><strong>Type:</strong> {{ ucfirst($order->order_type) }}</p>
                                                <p class="mb-1"><strong>Gallons:</strong> {{ $order->gallons }} L</p>
                                                <p class="mb-1"><strong>Total:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
                                                <p class="mb-0"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                                            </div>

                                            {{-- Delivery schedule --}}
                                            @if($order->order_type === 'delivery' && ($order->delivery_date || $order->delivery_time))
                                                <div class="col-12">
                                                    <h6 class="fw-bold"><i class="bi bi-truck me-2"></i>Delivery Schedule</h6>
                                                    <p class="mb-0">
                                                        {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') : '' }}
                                                        {{ $order->delivery_time ? '(' . $order->delivery_time . ')' : '' }}
                                                    </p>
                                                </div>
                                            @endif

                                            {{-- Notes --}}
                                            @if($order->notes)
                                                <div class="col-12">
                                                    <h6 class="fw-bold"><i class="bi bi-chat-left-text me-2"></i>Notes</h6>
                                                    <p class="mb-0">{{ $order->notes }}</p>
                                                </div>
                                            @endif

                                            {{-- Delete inline (password field appears in row — matches JSX deleteMode) --}}
                                            <div class="col-12">
                                                <div id="delete-btn-{{ $order->id }}">
                                                    <button
                                                        class="btn btn-sm btn-danger"
                                                        onclick="showDeleteInput({{ $order->id }})"
                                                        type="button"
                                                    >
                                                        <i class="bi bi-trash me-1"></i> Delete
                                                    </button>
                                                </div>

                                                <div id="delete-form-{{ $order->id }}" class="d-none">
                                                    <form
                                                        action="{{ route('admin.orders.delete', $order->id) }}"
                                                        method="POST"
                                                        class="d-flex gap-2 align-items-center"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <input
                                                            type="password"
                                                            name="password"
                                                            class="form-control form-control-sm"
                                                            style="width:160px;"
                                                            placeholder="Password"
                                                            required
                                                            autofocus
                                                        >
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-secondary"
                                                            onclick="hideDeleteInput({{ $order->id }})"
                                                        >Cancel</button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>{{-- /row --}}
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No orders yet. 📭</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Close type filter dropdown when clicking outside
    document.addEventListener('click', function (e) {
        const toggle   = document.getElementById('typeFilterToggle');
        const dropdown = document.getElementById('typeDropdown');
        if (dropdown && toggle && !toggle.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });

    // Inline delete toggle (mirrors JSX deleteMode state)
    function showDeleteInput(id) {
        document.getElementById('delete-btn-'  + id).classList.add('d-none');
        document.getElementById('delete-form-' + id).classList.remove('d-none');
        document.querySelector('#delete-form-' + id + ' input[type=password]').focus();
    }
    function hideDeleteInput(id) {
        document.getElementById('delete-form-' + id).classList.add('d-none');
        document.getElementById('delete-btn-'  + id).classList.remove('d-none');
    }
</script>
@endpush