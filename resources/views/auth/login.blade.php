@extends('layouts.auth')

@section('content')
<div class="login-container" style="max-width: 400px; margin: 0 auto; padding: 2rem;">
    <div class="login-box" style="background: #fff; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div class="login-header" style="text-align: center; margin-bottom: 2rem;">
            <img src="{{ asset('artifacts/company_logo_blue_square.jpeg') }}" alt="Company Logo" style="width: 48px; height: 48px; display: inline-block; vertical-align: middle; margin-right: 0.5rem;">
            <span style="font-weight: 700; font-size: 1.5rem; vertical-align: middle;">Money Pro</span>
        </div>

        <form method="POST" action="/duit/login">
            @csrf
            <h2 style="text-align: center; margin-bottom: 1.5rem;">Welcome Back</h2>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="email" style="display: block; margin-bottom: 0.5rem;">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; @error('email') border-color: #e53e3e; @enderror">
                @error('email')
                    <p style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; margin-bottom: 0.5rem;">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
                @error('password')
                    <p style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" style="width: 100%; background-color: #2563EB; color: white; padding: 0.75rem; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer;">
                Sign in
            </button>

            <div style="text-align: center; margin-top: 1rem;">
                <a href="#" style="color: #2563EB; font-size: 0.875rem; text-decoration: none;">Forgot password?</a>
            </div>
        </form>
    </div>

    <footer style="text-align: center; margin-top: 2rem; font-size: 0.875rem; color: #6B7280;">
        <a href="#" style="margin: 0 1rem; color: #6B7280; text-decoration: none;">Terms</a>
        <a href="#" style="margin: 0 1rem; color: #6B7280; text-decoration: none;">Privacy</a>
        <a href="#" style="margin: 0 1rem; color: #6B7280; text-decoration: none;">Support</a>
    </footer>
</div>
@endsection
