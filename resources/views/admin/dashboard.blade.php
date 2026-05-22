@extends('layouts.admin')

@section('title', 'Dashboard — AquaPure')

@section('content')

{{-- ===== ADMIN NAVBAR ===== --}}
<nav class="admin-navbar">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="admin-logo-icon">
                <i class="bi bi-droplet-fill"></i>
            </div>
            <span class="admin-brand">
                Aqua<span class="admin-brand-accent">Pure</span>
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            {{-- User info --}}
            <span class="text-white small me-2">
                <i class="bi bi-person-circle me-1"></i>
                {{ $user->username }} ({{ ucfirst($user->role) }})
            </span>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </a>
            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Exit
                </button>
            </form>
        </div>
    </div>
</nav>

{{-- ===== MAIN CONTENT ===== --}}
<div class="admin-content">
    <div class="container-fluid">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ===== STATS ===== --}}
        @php
            $walkIn   = $orderTypes->firstWhere('order_type', 'walk-in');
            $delivery = $orderTypes->firstWhere('order_type', 'delivery');
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-primary"><i class="bi bi-receipt"></i></div>
                    <div class="stat-content">
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h3 class="mb-0">{{ $totalOrders }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-warning"><i class="bi bi-hourglass-split"></i></div>
                    <div class="stat-content">
                        <h6 class="text-muted mb-1">Pending</h6>
                        <h3 class="mb-0">{{ $pendingOrders }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-success"><i class="bi bi-cash-coin"></i></div>
                    <div class="stat-content">
                        <h6 class="text-muted mb-1">Revenue (Completed)</h6>
                        <h3 class="mb-0">₱{{ number_format($totalRevenue, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="admin-stat-card">
                    <div class="stat-icon bg-info"><i class="bi bi-droplet-fill"></i></div>
                    <div class="stat-content">
                        <h6 class="text-muted mb-1">Gallons Sold</h6>
                        <h3 class="mb-0">{{ $totalGallons }}</h3>
                    </div>
                </div>
            </div>

            {{-- Walk-in & Delivery breakdown --}}
            <div class="col-md-6">
                <div class="admin-stat-card h-100">
                    <div class="stat-icon bg-success"><i class="bi bi-person-walking"></i></div>
                    <div class="stat-content grow">
                        <h6 class="text-muted mb-1">🚶 Walk-In Orders</h6>
                        <div class="d-flex justify-content-between align-items-end">
                            <h3 class="mb-0">{{ $walkIn->count ?? 0 }} orders</h3>
                            <span class="fw-bold fs-4">₱{{ number_format($walkIn->revenue ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="admin-stat-card h-100">
                    <div class="stat-icon bg-info"><i class="bi bi-truck"></i></div>
                    <div class="stat-content grow">
                        <h6 class="text-muted mb-1">🚚 Delivery Orders</h6>
                        <div class="d-flex justify-content-between align-items-end">
                            <h3 class="mb-0">{{ $delivery->count ?? 0 }} orders</h3>
                            <span class="fw-bold fs-4">₱{{ number_format($delivery->revenue ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- /STATS --}}

        {{-- ===== TABS ===== --}}
        @php $activeTab = request('tab', 'orders'); @endphp
        <div class="orders-section mb-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'orders' ? 'active' : '' }}"
                       href="{{ route('admin.dashboard', ['tab' => 'orders']) }}">
                        <i class="bi bi-bag-check me-2"></i>Orders ({{ $orders->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'messages' ? 'active' : '' }}"
                       href="{{ route('admin.dashboard', ['tab' => 'messages']) }}">
                        <i class="bi bi-chat-left-dots me-2"></i>Messages ({{ $messages->count() }})
                        @if($unreadCount > 0)
                            <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'reports' ? 'active' : '' }}"
                       href="{{ route('admin.dashboard', ['tab' => 'reports']) }}">
                        <i class="bi bi-bar-chart-line me-2"></i>Reports
                    </a>
                </li>

                {{-- Admin-only tabs --}}
                @if($user->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'pricing' ? 'active' : '' }}"
                       href="{{ route('admin.dashboard', ['tab' => 'pricing']) }}">
                        <i class="bi bi-currency-dollar me-2"></i>Pricing
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'logs' ? 'active' : '' }}"
                       href="{{ route('admin.dashboard', ['tab' => 'logs']) }}">
                        <i class="bi bi-journal-text me-2"></i>Activity Logs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'users' ? 'active' : '' }}"
                       href="{{ route('admin.dashboard', ['tab' => 'users']) }}">
                        <i class="bi bi-people-fill me-2"></i>Add User
                    </a>
                </li>
                @endif
            </ul>
        </div>

        {{-- ===== ORDERS TAB ===== --}}
        @if($activeTab === 'orders')
            @include('partials._orders-table')
        @endif

        {{-- ===== MESSAGES TAB ===== --}}
        @if($activeTab === 'messages')
        <div class="orders-section">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-chat-left-dots me-2"></i>Messages
            </h5>
            <div class="admin-table-container">
                <div class="table-responsive">
                    <table class="table table-hover admin-table">
                        <thead>
                            <tr class="table-header">
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>SUBJECT</th>
                                <th>STATUS</th>
                                <th>DATE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                                @php
                                    $statusMap = [
                                        'unread'  => ['label' => '🔴 Unread',  'badge' => 'bg-danger'],
                                        'read'    => ['label' => '📖 Read',    'badge' => 'bg-warning text-dark'],
                                        'replied' => ['label' => '✅ Replied', 'badge' => 'bg-success'],
                                    ];
                                    $cfg = $statusMap[$msg->status] ?? ['label' => $msg->status, 'badge' => 'bg-secondary'];
                                @endphp
                                <tr class="table-row">
                                    <td class="fw-semibold">{{ $msg->full_name }}</td>
                                    <td class="text-muted small">{{ $msg->email_address ?: '—' }}</td>
                                    <td>{{ $msg->subject }}</td>
                                    <td>
                                        <span class="badge {{ $cfg['badge'] }}">{{ $cfg['label'] }}</span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $msg->created_at->format('M d, Y g:i A') }}
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#msg-detail-{{ $msg->id }}"
                                        >View</button>
                                    </td>
                                </tr>

                                {{-- Expanded message --}}
                                <tr class="collapse-row">
                                    <td colspan="6" class="p-0">
                                        <div class="collapse" id="msg-detail-{{ $msg->id }}">
                                            <div class="order-details-expanded p-3 bg-light">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold mb-2">
                                                            <i class="bi bi-person-fill me-2"></i>Sender Info
                                                        </h6>
                                                        <p class="mb-1"><strong>Name:</strong> {{ $msg->full_name }}</p>
                                                        <p class="mb-1"><strong>Email:</strong> {{ $msg->email_address ?: '—' }}</p>
                                                        <p class="mb-0"><strong>Phone:</strong> {{ $msg->phone_number }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold mb-2">
                                                            <i class="bi bi-info-circle me-2"></i>Message Info
                                                        </h6>
                                                        <p class="mb-1"><strong>Subject:</strong> {{ $msg->subject }}</p>
                                                        <p class="mb-0">
                                                            <strong>Status:</strong>
                                                            <span class="badge {{ $cfg['badge'] }}">{{ $cfg['label'] }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="col-12">
                                                        <h6 class="fw-bold mb-2">
                                                            <i class="bi bi-chat-left-text me-2"></i>Message
                                                        </h6>
                                                        <div class="bg-white p-2 rounded" style="min-height:100px; white-space:pre-wrap;">
                                                            {{ $msg->message }}
                                                        </div>
                                                    </div>

                                                    {{-- Update status --}}
                                                    <div class="col-md-6">
                                                        <form action="{{ route('admin.messages.update', $msg->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                                                            @csrf
                                                            @method('PUT')
                                                            <select name="status" class="form-select form-select-sm">
                                                                @foreach(['unread','read','replied'] as $s)
                                                                    <option value="{{ $s }}" {{ $msg->status === $s ? 'selected' : '' }}>
                                                                        {{ ucfirst($s) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-primary text-nowrap">
                                                                <i class="bi bi-check-lg me-1"></i>Update
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No messages yet. 📭</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        {{-- /MESSAGES TAB --}}

        {{-- ===== REPORTS TAB ===== --}}
        @if($activeTab === 'reports')
        <div class="orders-section">
            {{-- Filter bar --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                <h5 class="fw-bold mb-0 me-auto">
                    <i class="bi bi-bar-chart-fill me-2"></i>Sales Reports
                </h5>
                <div class="btn-group">
                    @foreach(['all', 'daily', 'weekly', 'monthly', 'custom'] as $p)
                        <a href="{{ route('admin.dashboard', array_merge(request()->except('period'), ['tab' => 'reports', 'period' => $p])) }}"
                           class="btn btn-sm {{ $period === $p ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ ucfirst($p) }}
                        </a>
                    @endforeach
                </div>
                @if($period === 'custom')
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex gap-2 align-items-center">
                        <input type="hidden" name="tab" value="reports">
                        <input type="hidden" name="period" value="custom">
                        <input type="date" name="start" class="form-control form-control-sm"
                               value="{{ $start ?? '' }}" required>
                        <span>to</span>
                        <input type="date" name="end" class="form-control form-control-sm"
                               value="{{ $end ?? '' }}" required>
                        <button type="submit" class="btn btn-sm btn-success">Generate</button>
                    </form>
                @endif
                <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>

            {{-- Stat Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="admin-stat-card">
                        <div class="stat-icon bg-primary"><i class="bi bi-receipt"></i></div>
                        <div class="stat-content">
                            <h6 class="text-muted">Total Orders</h6>
                            <h3>{{ $reportStats['totalOrders'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="admin-stat-card">
                        <div class="stat-icon bg-success"><i class="bi bi-cash-coin"></i></div>
                        <div class="stat-content">
                            <h6 class="text-muted">Total Revenue</h6>
                            <h3>₱{{ number_format($reportStats['totalRevenue'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="admin-stat-card">
                        <div class="stat-icon bg-info"><i class="bi bi-droplet-fill"></i></div>
                        <div class="stat-content">
                            <h6 class="text-muted">Total Gallons</h6>
                            <h3>{{ $reportStats['totalGallons'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="admin-stat-card">
                        <div class="stat-icon bg-warning"><i class="bi bi-graph-up"></i></div>
                        <div class="stat-content">
                            <h6 class="text-muted">Avg. Order Value</h6>
                            <h3>₱{{ number_format($reportStats['avgOrderValue'], 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Type Breakdown --}}
            <div class="mb-4">
                <h6 class="fw-bold">Order Type Breakdown</h6>
                <div class="row g-2">
                    @forelse($reportBreakdown as $b)
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between bg-light p-2 rounded">
                                <span class="fw-semibold">{{ ucfirst($b->order_type) }}</span>
                                <span>{{ $b->count }} orders — ₱{{ number_format($b->revenue, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No data for this period.</p>
                    @endforelse
                </div>
            </div>

            {{-- Orders Table --}}
            <h6 class="fw-bold mb-2">Orders in this period</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Gallons</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportOrders as $ro)
                            <tr>
                                <td>{{ $ro->id }}</td>
                                <td>{{ $ro->customer_name ?: '—' }}</td>
                                <td>{{ ucfirst($ro->order_type) }}</td>
                                <td>{{ $ro->gallons }}</td>
                                <td>₱{{ number_format($ro->total_price, 2) }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($ro->status) }}</span></td>
                                <td>{{ $ro->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($reportOrders->count() > 0)
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="fw-bold text-end">Totals:</td>
                            <td class="fw-bold">{{ $reportStats['totalGallons'] }} gal</td>
                            <td class="fw-bold">₱{{ number_format($reportStats['totalRevenue'], 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @endif
        {{-- /REPORTS TAB --}}

        {{-- ===== ACTIVITY LOGS TAB (admin only) ===== --}}
        @if($activeTab === 'logs' && $user->isAdmin())
        <div class="orders-section">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-journal-text me-2"></i>Activity Log
            </h5>
            <div class="admin-table-container">
                <div class="table-responsive">
                    <table class="table table-hover admin-table">
                        <thead>
                            <tr class="table-header">
                                <th>#</th>
                                <th>ACTION</th>
                                <th>DESCRIPTION</th>
                                <th>USER</th>
                                <th>DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $log)
                                <tr>
                                    <td class="text-muted small">{{ $log->id }}</td>
                                    <td><span class="badge bg-secondary">{{ $log->action }}</span></td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->admin_user }}</td>
                                    <td class="text-muted small">{{ $log->created_at->format('M d, Y g:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No activity logs yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        {{-- /ACTIVITY LOGS TAB --}}

        {{-- ===== PRICING TAB (admin only) ===== --}}
        @if($activeTab === 'pricing' && $user->isAdmin())
            @include('partials._pricing-form')
        @endif
        {{-- /PRICING TAB --}}

        {{-- ===== USERS TAB (admin only) ===== --}}
        @if($activeTab === 'users' && $user->isAdmin())
            @include('partials._add-user-form', ['cashiers' => $cashiers])
        @endif
        {{-- /USERS TAB --}}

    </div>
</div>

@endsection