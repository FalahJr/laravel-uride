@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-card">
        <div class="auth-left">
            <div class="auth-logo">
                <a href="{{ url('/') }}"><img src="/mazer/assets/static/images/logo/logo.svg" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Welcome to Uride</h1>
            <p class="auth-subtitle mb-4">A campus ride-sharing platform — sign in to manage rides and drivers.</p>

            <form method="POST" action="{{ route('login.post') }}" class="form-card">
                @csrf
                <div class="form-group position-relative has-icon-left mb-4">
                    <input name="username" value="{{ old('username') }}" type="text"
                        class="form-control form-control-xl @error('username') is-invalid @enderror" placeholder="Username">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input name="password" type="password"
                        class="form-control form-control-xl @error('password') is-invalid @enderror" placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check form-check-lg d-flex align-items-center mb-4">
                    <input name="remember" class="form-check-input me-2" type="checkbox" value="1"
                        id="flexCheckDefault">
                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                        Keep me logged in
                    </label>
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-2">Log in</button>
            </form>

            <div class="text-center mt-4 text-sm">
                <p class="text-gray-600">Don't have an account? <a href="#" class="font-bold">Sign up</a>.</p>
            </div>
        </div>

        <div class="auth-right" style="background-image:url('/mazer/assets/static/images/samples/bg-mountain.jpg')">
            <div class="overlay"></div>
            <div class="hero">
                <h2>Uride</h2>
                <p>Fast campus rides — drivers and passengers connected in minutes.</p>
            </div>
        </div>
    </div>
@endsection
