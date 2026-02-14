@extends('layouts.auth')

@section('content')

<style>
    body{
        background: linear-gradient(135deg,#4f46e5,#06b6d4);
        min-height:100vh;
    }

    .register-card{
        border:none;
        border-radius:20px;
        backdrop-filter: blur(14px);
        background: rgba(255,255,255,0.90);
        box-shadow:0 20px 40px rgba(0,0,0,.25);
    }

    .register-title{
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

    .btn-register{
        border-radius:12px;
        padding:12px;
        font-weight:600;
        background: linear-gradient(135deg,#4f46e5,#06b6d4);
        border:none;
    }

    .btn-register:hover{
        opacity:.9;
        transform:translateY(-1px);
    }

    .logo{
        font-size:40px;
    }
</style>


<div class="container d-flex align-items-center justify-content-center" style="min-height:90vh">

    <div class="col-md-6">
        <div class="card register-card p-4">

            <div class="text-center mb-4">
                <div class="logo">📝</div>
                <h3 class="register-title mt-2">Buat Akun Baru</h3>
                <small class="text-muted">Daftar untuk mengakses sistem koleksi buku</small>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input id="name" type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap..."
                        required autofocus>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input id="email" type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email..."
                        required>

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        required>

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KONFIRMASI --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <input id="password-confirm" type="password"
                        class="form-control"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        required>
                </div>

                {{-- BUTTON --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-register text-white">
                        Daftar Sekarang
                    </button>
                </div>

                {{-- LINK LOGIN --}}
                <div class="text-center mt-3">
                    <small class="text-muted">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                            Login disini
                        </a>
                    </small>
                </div>

            </form>

        </div>

        <p class="text-center text-white mt-3 small">
            © {{ date('Y') }} Sistem Koleksi Buku
        </p>
    </div>

</div>

@endsection
