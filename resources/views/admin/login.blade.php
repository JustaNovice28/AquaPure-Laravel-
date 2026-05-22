<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — AquaPure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @vite(['resources/css/admin.css'])
</head>
<body>

<div class="admin-login-container">
    <div class="admin-login-box">

        <div class="admin-login-header">
            <div class="logo-icon-large">
                <i class="bi bi-droplet-fill"></i>
            </div>
            <h2>AquaPure Panel</h2>
            <p>Admin & Cashier Login</p>
        </div>

        {{-- Error flash from controller --}}
        @if(session('error'))
            <div class="alert alert-danger mb-3">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf

            {{-- Username --}}
            <div class="mb-3">
                <label class="form-label fw-semibold" for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control admin-input"
                    placeholder="Enter your username"
                    value="{{ old('username') }}"
                    autofocus
                    required
                >
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label fw-semibold" for="password">Password</label>
                <div class="position-relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control admin-input pe-5"
                        placeholder="Enter your password"
                        required
                    >
                    <button
                        type="button"
                        id="togglePw"
                        class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent text-muted"
                        tabindex="-1"
                        aria-label="Toggle password visibility"
                    >
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button
                type="submit"
                class="btn btn-primary w-100 py-3"
                style="border-radius: 12px; font-weight: 600;"
            >
                <i class="bi bi-unlock-fill me-2"></i>Log In
            </button>
        </form>

        <hr class="my-4">

        <small class="text-muted d-block text-center">
            <i class="bi bi-shield-check text-info me-1"></i>Authorized personnel only.
        </small>

        <div class="text-center mt-3">
            <a href="{{ url('/') }}" class="admin-login-back">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePw').addEventListener('click', function () {
        const pw   = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (pw.type === 'password') {
            pw.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            pw.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>

</body>
</html>