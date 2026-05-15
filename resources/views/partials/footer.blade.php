<footer class="footer">
    <div class="container">
        <div class="row g-4">

            {{-- Brand --}}
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="logo-icon">
                        <i class="bi bi-droplet-fill"></i>
                    </div>
                    <span class="brand-text text-white">
                        Aqua<span class="brand-accent">Pure</span>
                    </span>
                </div>
                <p class="footer-desc">
                    Providing clean, affordable, and safe purified water to the community
                    of Barra, Opol, Misamis Oriental.
                </p>
                <div class="footer-socials mt-3">
                    <a href="https://www.facebook.com/AquaPureBarra" aria-label="Facebook"
                       target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/AquaPureBarra" aria-label="Instagram"
                       target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://m.me/AquaPureBarra" aria-label="Messenger"
                       target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-messenger"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-md-2 offset-md-1">
                <h6 class="footer-heading">Quick Links</h6>
                <ul class="footer-links">
                    @foreach(['home','about','features','services','order','team','contact'] as $link)
                    <li>
                        <a href="#{{ $link }}"
                           onclick="event.preventDefault(); scrollToSection('{{ $link }}')">
                            {{ ucfirst($link) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Services --}}
            <div class="col-md-2">
                <h6 class="footer-heading">Services</h6>
                <ul class="footer-links">
                    @foreach(['Walk-In Refill', 'Home Delivery', 'Suki / Bulk Orders'] as $service)
                    <li>
                        <a href="#services"
                           onclick="event.preventDefault(); scrollToSection('services')">
                            {{ $service }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div class="col-md-3">
                <h6 class="footer-heading">Contact</h6>
                <ul class="footer-contact">
                    <li><i class="bi bi-geo-alt-fill"></i> Barra, Opol Mis. Or.</li>
                    <li><i class="bi bi-facebook"></i> facebook.com/AquaPureBarra</li>
                    <li><i class="bi bi-envelope-fill"></i> aquapure.barra@gmail.com</li>
                </ul>
            </div>

        </div>

        <hr class="footer-divider">
        <div class="footer-bottom">
            <p>© 2026 AquaPure Water Refilling Station. All Rights Reserved.</p>
            <p>Developed for <strong>BSIT Capstone Project</strong> – Barra, Opol, Mis. Or.</p>
        </div>
    </div>
</footer>

{{-- Scroll To Top Button --}}
<button id="scrollTopBtn"
        onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
        style="display:none; position:fixed; bottom:30px; right:30px;
               z-index:999; width:45px; height:45px; border-radius:50%;
               border:none; background:var(--primary); color:white;
               align-items:center; justify-content:center; cursor:pointer;
               box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
    <i class="bi bi-arrow-up"></i>
</button>