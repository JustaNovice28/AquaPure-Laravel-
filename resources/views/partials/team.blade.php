<section class="section-padding" id="team">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-badge">The Team</span>
            <h2 class="section-title">Meet the Developers</h2>
            <p class="section-subtitle">
                The people behind the AquaPure Water Refilling Station Management System.
            </p>
        </div>

        <div class="row justify-content-center g-4">
            @foreach([
                ['name' => 'Aronn John A. Tumampos', 'role' => 'Lead Developer',        'desc' => 'Responsible for system architecture and back-end development.'],
                ['name' => 'Jhon Zinoel Buado',      'role' => 'UI/UX Designer',         'desc' => 'Designed the user interface and overall user experience flow.'],
                ['name' => 'Marvel Lumbab',           'role' => 'Front-End Developer',   'desc' => 'Built all the responsive web pages and interactive components.'],
                ['name' => 'Zyrel Cahigao',           'role' => 'Database Administrator','desc' => 'Managed database design, optimization, and data security.'],
            ] as $member)
            <div class="col-sm-6 col-lg-3">
                <div class="team-card text-center h-100">
                    <div class="team-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h5 class="team-name">{{ $member['name'] }}</h5>
                    <p class="team-role">{{ $member['role'] }}</p>
                    <p class="team-desc">{{ $member['desc'] }}</p>
                    <div class="team-socials">
                        <a href="#" aria-label="{{ $member['name'] }} on GitHub">
                            <i class="bi bi-github"></i>
                        </a>
                        <a href="#" aria-label="{{ $member['name'] }} on LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" aria-label="Email {{ $member['name'] }}">
                            <i class="bi bi-envelope-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>