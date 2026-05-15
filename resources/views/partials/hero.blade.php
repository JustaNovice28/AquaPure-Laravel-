<section class="hero-section d-flex align-items-center" id="home">
    <div class="hero-overlay"></div>

    <div class="container position-relative text-center text-white">

        <p class="hero-location animate-fade">
            <i class="bi bi-geo-alt-fill text-warning"></i>
            &nbsp;Barra, Opol Mis. Or.
        </p>

        <h1 class="hero-title animate-fade delay-1">
            Unlimited Pure, Mineralized<br>
            Water for <span class="price-highlight">₱25 pesos</span><br>
            When Refilling
        </h1>

        <p class="hero-subtitle animate-fade delay-2">
            No more microplastics, toxins, or bottle waste.<br>
            Just clean, healthy water for total peace of mind.
        </p>

        <div class="d-flex flex-wrap justify-content-center gap-3 mt-4 animate-fade delay-3">
            <a href="https://m.me/AquaPureBarra"
               class="btn btn-messenger btn-lg"
               target="_blank" rel="noopener noreferrer">
                <i class="bi bi-messenger me-2"></i>Chat To Start
            </a>
            <a href="#about"
               class="btn btn-outline-light btn-lg px-4"
               onclick="event.preventDefault(); scrollToSection('about')">
                Learn More <i class="bi bi-arrow-down ms-1"></i>
            </a>
        </div>

        {{-- Stats --}}
        <div class="hero-stats row justify-content-center mt-5 animate-fade delay-3" id="heroStats">
            @foreach([
                ['target' => 500, 'suffix' => '+',  'label' => 'Happy Customers'],
                ['target' => 5,   'suffix' => '+',  'label' => 'Years of Service'],
                ['target' => 99,  'suffix' => '%',  'label' => 'Pure Water Quality'],
                ['target' => 7,  'suffix' => ' Days', 'label' => 'Service Available'],
            ] as $stat)
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <span class="stat-number" data-target="{{ $stat['target'] }}">0</span>
                    <span class="stat-plus">{{ $stat['suffix'] }}</span>
                    <p>{{ $stat['label'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- Counter Animation --}}
@push('scripts')
<script>
    const statsEl = document.getElementById('heroStats');
    let counted = false;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !counted) {
                counted = true;
                document.querySelectorAll('.stat-number').forEach(counter => {
                    const target = +counter.dataset.target;
                    const step = target / (1800 / 16);
                    let current = 0;
                    const update = () => {
                        current += step;
                        if (current < target) {
                            counter.textContent = Math.floor(current);
                            requestAnimationFrame(update);
                        } else {
                            counter.textContent = target;
                        }
                    };
                    requestAnimationFrame(update);
                });
                observer.disconnect();
            }
        });
    }, { threshold: 0.3 });

    if (statsEl) observer.observe(statsEl);
</script>
@endpush