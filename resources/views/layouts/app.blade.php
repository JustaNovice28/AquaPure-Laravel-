<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AquaPure Water Refilling Station')</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"/>

    {{-- Your CSS files --}}
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Global JS: Smooth Scroll + Active Section + Secret Trigger --}}
    <script>
        // ── Smooth scroll helper ──────────────────────────────────────
        function scrollToSection(sectionId) {
            const el = document.getElementById(sectionId);
            if (el) {
                const top = el.getBoundingClientRect().top + window.scrollY - 80;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        }

        // ── Navbar: scroll background + active section ────────────────
        const navbar = document.getElementById('mainNavbar');
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link[data-section]');

        window.addEventListener('scroll', () => {
            // Scrolled class
            navbar.classList.toggle('scrolled', window.scrollY > 60);

            // Active section highlight
            let current = 'home';
            sections.forEach(section => {
                if (window.scrollY >= section.offsetTop - 90) {
                    current = section.getAttribute('id');
                }
            });
            navLinks.forEach(link => {
                link.classList.toggle('active', link.dataset.section === current);
            });
        });

        // ── Navbar: mobile toggle ─────────────────────────────────────
        document.querySelector('.navbar-toggler')?.addEventListener('click', () => {
            document.getElementById('navbarMenu').classList.toggle('show');
        });

        // ── Scroll-to-top button ──────────────────────────────────────
        window.addEventListener('scroll', () => {
            const btn = document.getElementById('scrollTopBtn');
            if (btn) btn.style.display = window.scrollY > 400 ? 'flex' : 'none';
        });

        // ── Secret Admin Trigger: type "opensesame" anywhere ──────────
        let typed = '';
        const SECRET = 'opensesame';
        document.addEventListener('keydown', (e) => {
            typed += e.key.toLowerCase();
            if (typed.length > SECRET.length) {
                typed = typed.slice(-SECRET.length);
            }
            if (typed === SECRET) {
                window.location.href = '{{ route("admin.login") }}';
            }
        });
    </script>

    @stack('scripts')
</body>
</html>