<section class="section-padding" id="features">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">System Features</span>
            <h2 class="section-title">What the System Can Do</h2>
            <p class="section-subtitle">
                Comprehensive tools designed to simplify every aspect of your water
                refilling business.
            </p>
        </div>

        <div class="row g-4">
            @foreach([
                [
                    'icon' => 'bi-cart-fill',
                    'color' => 'text-primary',
                    'bg' => 'bg-primary-soft',
                    'title' => 'Online Order Management',
                    'desc' => 'Customers can place orders online for water refill or delivery. Staff can view, update, and manage all orders in real time.',
                    'list' => ['Real-time order tracking', 'Order history & receipts', 'Automated order confirmation'],
                ],
                [
                    'icon' => 'bi-bar-chart-fill',
                    'color' => 'text-info',
                    'bg' => 'bg-info-soft',
                    'title' => 'Sales & Reports',
                    'desc' => 'Generate detailed sales reports by day, week, or month. Gain insights to make smarter business decisions.',
                    'list' => ['Daily/weekly/monthly reports', 'Revenue analytics', 'Printable PDF reports'],
                ],
                [
                    'icon' => 'bi-shield-lock-fill',
                    'color' => 'text-purple',
                    'bg' => 'bg-purple-soft',
                    'title' => 'User Access Control',
                    'desc' => 'Role-based access for admin, staff, and customers ensures data security and proper system usage.',
                    'list' => ['Admin / Staff / Customer roles', 'Secure login & authentication', 'Activity logs & audit trails'],
                ],
            ] as $feature)
            <div class="col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="feature-icon {{ $feature['bg'] }}">
                        <i class="bi {{ $feature['icon'] }} {{ $feature['color'] }}"></i>
                    </div>
                    <h5>{{ $feature['title'] }}</h5>
                    <p>{{ $feature['desc'] }}</p>
                    <ul class="feature-list">
                        @foreach($feature['list'] as $item)
                        <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>