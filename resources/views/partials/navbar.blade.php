<nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand d-flex align-items-center gap-2"
           href="#home" onclick="event.preventDefault(); scrollToSection('home')">
            <div class="logo-icon">
                <i class="bi bi-droplet-fill"></i>
            </div>
            <span class="brand-text">
                Aqua<span class="brand-accent">Pure</span>
            </span>
        </a>

        {{-- Hamburger --}}
        <button class="navbar-toggler border-0" type="button" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Nav Links --}}
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                @foreach(['home', 'services', 'order', 'contact'] as $item)
                <li class="nav-item">
                    <a class="nav-link {{ $item === 'home' ? 'active' : '' }}"
                       href="#{{ $item }}"
                       data-section="{{ $item }}"
                       onclick="event.preventDefault(); scrollToSection('{{ $item }}')">
                        {{ ucfirst($item) }}
                    </a>
                </li>
                @endforeach

                <li class="nav-item ms-lg-3">
                    <a class="btn btn-cta" href="tel:+639123456789">
                        <i class="bi bi-telephone-fill"></i> +63 912 345 6789
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>