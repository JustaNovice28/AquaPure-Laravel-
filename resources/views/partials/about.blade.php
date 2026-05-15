<section class="section-padding bg-light" id="about">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">About the Project</span>
            <h2 class="section-title">What is AquaPure System?</h2>
            <p class="section-subtitle">
                A smart management system designed to streamline water refilling
                station operations in Barra, Opol, Misamis Oriental.
            </p>
        </div>

        <div class="row align-items-center g-5">
            {{-- Visual Side --}}
            <div class="col-lg-5">
                <div class="about-visual">
                    <div class="about-image-box">
                        <div class="about-icon-big">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                    </div>
                    <div class="floating-badge badge-left">
                        <i class="bi bi-shield-check text-success"></i>
                        <span>100% Safe</span>
                    </div>
                    <div class="floating-badge badge-right">
                        <i class="bi bi-currency-exchange text-primary"></i>
                        <span>₱25 Only</span>
                    </div>
                </div>
            </div>

            {{-- Text Side --}}
            <div class="col-lg-7">
                <h3 class="about-heading">Background &amp; Purpose</h3>
                <p class="about-text">
                    The <strong>AquaPure Water Refilling Station Management System</strong>
                    is a proposed web-based solution to address the operational challenges
                    faced by local water refilling stations as managing orders and
                    deliveries manually leads to errors and inefficiencies.
                </p>
                <p class="about-text">
                    This system aims to digitize and automate the core processes of a
                    water refilling business — from order tracking to delivery scheduling
                    and sales reporting — providing a reliable, affordable service to the
                    community of Barra, Opol, Misamis Oriental.
                </p>

                <h5 class="mt-4 mb-3 fw-semibold">Project Objectives</h5>
                <ul class="objective-list">
                    @foreach([
                        'Provide an online ordering system for walk-in and delivery customers',
                        'Provide staff with tools to manage orders and messages in a secure database',
                        'Allow customers to schedule their preferred delivery date and time',
                        'Generate daily, weekly, and monthly sales reports',
                        'Provide an easy-to-use interface for both staff and customers',
                    ] as $obj)
                    <li>
                        <i class="bi bi-check-circle-fill text-primary"></i>
                        {{ $obj }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>