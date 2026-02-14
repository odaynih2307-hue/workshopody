@extends('layouts.auth')

@section('content')

<style>
.login-card{
    border:none;
    border-radius:20px;
    backdrop-filter: blur(14px);
    background: rgba(255,255,255,0.92);
    box-shadow:0 20px 40px rgba(0,0,0,.25);
}

.login-title{
    font-weight:700;
    letter-spacing:.5px;
    color:#1e293b;
}

.form-control{
    border-radius:12px;
    padding:12px 14px;
}

.form-control:focus{
    box-shadow:0 0 0 .2rem rgba(79,70,229,.25);
    border-color:#6366f1;
}

.btn-login{
    border-radius:12px;
    padding:12px;
    font-weight:600;
    background: linear-gradient(135deg,#4f46e5,#06b6d4);
    border:none;
}

.btn-login:hover{
    opacity:.95;
    transform:translateY(-1px);
}

.login-logo{
    font-size:40px;
}
</style>


<div class="w-100">

    <div class="card login-card p-4">

        <div class="text-center mb-4">
            <div class="login-logo">📚</div>
            <h3 class="login-title mt-2">Koleksi Buku</h3>
            <small class="text-muted">Masuk ke sistem</small>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- EMAIL --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input id="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="contoh@email.com"
                    required autofocus>

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    placeholder="••••••••"
                    required>

                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- REMEMBER --}}
            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label text-muted" for="remember">
                    Ingat saya
                </label>
            </div>

            {{-- BUTTON --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-login text-white">
                    Login
                </button>
            </div>

        </form>

        {{-- REGISTER LINK --}}
        <div class="text-center mt-4 small">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                Daftar disini
            </a>
        </div>

        {{-- FORGOT --}}
        @if (Route::has('password.request'))
        <div class="text-center mt-2">
            <a class="text-muted small text-decoration-none" href="{{ route('password.request') }}">
                Lupa password?
            </a>
        </div>
        @endif

    </div>

    <p class="text-center text-white mt-3 small">
        © {{ date('Y') }} Sistem Koleksi Buku
    </p>

</div>

@endsection
