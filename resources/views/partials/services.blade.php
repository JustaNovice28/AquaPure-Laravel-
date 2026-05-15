<section class="section-padding bg-light" id="services">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">Our Services</span>
            <h2 class="section-title">What We Offer</h2>
            <p class="section-subtitle">
                Affordable, clean, and reliable water for every household in Barra, Opol.
            </p>
        </div>

        {{-- Service Cards --}}
        <div class="row g-4 mb-5">
            @foreach([
                [
                    'icon' => 'bi-droplet-fill',
                    'title' => 'Walk-In Refill',
                    'desc' => 'Bring your own gallon container to our station and get it refilled with pure, mineralized water.',
                    'price' => '₱25',
                    'unit' => '/ gallon',
                    'popular' => false,
                    'highlights' => ['Bring your own container', 'Refill while you wait', 'Open Mon-Sat 6AM-8PM'],
                ],
                [
                    'icon' => 'bi-truck',
                    'title' => 'Home Delivery',
                    'desc' => 'Order by call, text, or Messenger and we deliver clean water straight to your doorstep.',
                    'price' => '₱30',
                    'unit' => '/ gallon (delivered)',
                    'popular' => true,
                    'highlights' => ['Delivered to your door', 'Same-day delivery available', 'Covers Barra & nearby areas'],
                ],
                [
                    'icon' => 'bi-people-fill',
                    'title' => 'Suki / Bulk Orders',
                    'desc' => 'Regular customer? Order 5 or more gallons and enjoy free delivery with our suki price.',
                    'price' => '₱25',
                    'unit' => '/ gallon (5+ gallons, FREE delivery)',
                    'popular' => false,
                    'highlights' => ['Free delivery for 5+ gallons', 'Same low walk-in price', 'Priority service for regulars'],
                ],
            ] as $service)
            <div class="col-md-4">
                <div class="service-card text-center h-100 {{ $service['popular'] ? 'featured-service' : '' }}">
                    @if($service['popular'])
                        <div class="popular-badge">Most Popular</div>
                    @endif
                    <div class="service-icon">
                        <i class="bi {{ $service['icon'] }}"></i>
                    </div>
                    <h5>{{ $service['title'] }}</h5>
                    <p>{{ $service['desc'] }}</p>
                    <ul class="service-highlights">
                        @foreach($service['highlights'] as $item)
                        <li>
                            <i class="bi bi-check2 text-success me-1"></i>{{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    <div class="service-price mt-auto">
                        {{ $service['price'] }} <small>{{ $service['unit'] }}</small>
                    </div>
                    <a href="#order"
                       class="btn btn-primary btn-sm mt-3 w-100"
                       onclick="event.preventDefault(); scrollToSection('order')">
                        <i class="bi bi-cart-plus me-1"></i> Order Now
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- FAQ --}}
        <h4 class="text-center fw-bold mb-4">Frequently Asked Questions</h4>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion custom-accordion" id="faqAccordion">
                    @foreach([
                        [
                            'q' => 'What purification process do you use?',
                            'a' => 'We use a multi-stage filtration process including sediment filters, activated carbon, reverse osmosis (RO), UV sterilization, and mineral addition to ensure every drop is clean, safe, and healthy.',
                        ],
                        [
                            'q' => 'How do I place a delivery order?',
                            'a' => 'Place an order online on our website or just message us on our Facebook page, send us a text, or call our number. Tell us your address and how many gallons you need. We\'ll deliver within the day!',
                        ],
                        [
                            'q' => 'What areas do you deliver to?',
                            'a' => 'We deliver within Barra, Opol, Misamis Oriental and nearby barangays. Contact us to check if your area is covered.',
                        ],
                        [
                            'q' => 'Is the water safe for babies and elderly?',
                            'a' => 'Yes! Our water passes strict quality standards and is mineral-enriched, making it safe for all ages including babies and senior citizens.',
                        ],
                        [
                            'q' => 'Can I order for my business or store?',
                            'a' => 'Absolutely! We handle bulk orders for businesses, stores, and offices. Contact us for special bulk pricing on large orders.',
                        ],
                    ] as $i => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $i !== 0 ? 'collapsed' : '' }}"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq{{ $i }}">
                                {{ $faq['q'] }}
                            </button>
                        </h2>
                        <div id="faq{{ $i }}"
                             class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">{{ $faq['a'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>