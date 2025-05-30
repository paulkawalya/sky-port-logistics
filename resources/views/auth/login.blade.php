@extends('layouts.auth')

@section('content')
<style>
    .form-check-input:checked {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .form-switch .form-check-input:focus {
        border-color: rgba(220, 53, 69, 0.25);
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
    .form-switch .form-check-input:checked {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }
</style>

    <div class="row hp-authentication-page">
        <div class="hp-bg-black-20 hp-bg-color-dark-90 d-none d-lg-block col-lg-6">
            <div class="row hp-image-row h-100 px-8 px-sm-16 px-md-0 pb-32 pb-sm-0 pt-32 pt-md-0">
                <div class="hp-logo-item m-16 m-sm-32 m-md-64 w-auto px-0 col-12">
                    <div class="hp-header-logo d-flex align-items-center">
                        <a href="#" class="position-relative">

                            <img class="hp-logo hp-sidebar-visible hp-dark-none" src="app-assets/img/logo/tonightgroup.png" alt="logo">
                            <img class="hp-logo hp-sidebar-visible hp-dark-block" src="app-assets/img/logo/tonightgroup.png" alt="logo">
                            <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-none" src="app-assets/img/logo/tonightgroup.png" alt="logo">
                            <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-block" src="app-assets/img/logo/tonightgroup.png" alt="logo">
                            <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-none" src="app-assets/img/logo/tonightgroup.png" alt="logo">
                            <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-block" src="app-assets/img/logo/tonightgroup.png" alt="logo">
                        </a>


                    </div>
                </div>

                <div class="col-12 px-0">
                    <div class="row h-100 w-100 mx-0 align-items-center justify-content-center">
                        <div class="hp-bg-item text-center mb-8 mb-md-0 px-0 col-12">
                            <img class="hp-dark-none m-auto w-100" src="app-assets/img/pages/authentication/authentication-bg.svg" alt="Background Image">
                            <img class="hp-dark-block m-auto w-100" src="app-assets/img/pages/authentication/authentication-bg-dark.svg" alt="Background Image">
                        </div>

                        <div class="hp-text-item text-center mb-8 col-xl-9 col-12">
                            <h2 class="hp-text-color-black-100 hp-text-color-dark-0 mx-16 mx-lg-0 mb-16"> Welcome to SifCargo Group </h2>
                            <p class="h4 mb-0 fw-normal hp-text-color-black-80 hp-text-color-dark-30">Work Smarter and create your workflows with ease</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 py-32 py-sm-64 py-lg-0">
            <div class="row align-items-center justify-content-center h-100 mx-0 mx-sm-n32">
                <div class="col-12 col-sm-10 col-md-8 col-lg-9 col-xl-7 px-8 px-sm-8 px-md-0 pt-16 pt-sm-24 pb-32 pb-sm-48">
                    <div class="d-lg-none text-center mb-16">
                        <img src="app-assets/img/logo/tonightgroup.png" alt="Logo" style="max-height: 60px;">
                    </div>
                    <h1 class="mb-0 mb-sm-24" style="font-size: 1.75rem;">Login</h1>
                    <p class="mt-8 text-black-60" style="font-size: 1.1rem;">Welcome back, please login to your account.</p>

                    <form method="POST" action="{{ route('login') }}" class="mt-8 mt-sm-16 mb-8" style="font-size: 1rem;">
                        @csrf

                        <div class="mb-8 mb-sm-16">
                            <label for="email" class="form-label" style="font-size: 1rem;">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="padding: 0.75rem; font-size: 1rem;">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="mb-16">
                            <label for="password" class="form-label" style="font-size: 1rem;">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="padding: 0.75rem; font-size: 1rem;">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="mb-8 mb-sm-16">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                <label class="form-check-label" for="remember" style="font-size: 1rem;">
                                    <span class="ms-2">{{ __('Keep me logged in') }}</span>
                                    <small class="d-block text-muted ms-2" style="font-size: 0.85rem;">Recommended for private devices</small>
                                </label>
                            </div>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="mb-16 text-end">
                                <a class="hp-button text-black-80 hp-text-color-dark-40" href="{{ route('password.request') }}" style="font-size: 1rem;">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif


                        <button type="submit" class="btn btn-danger w-100">
                            {{ __('Login') }}
                        </button>

                    </form>


                    <div class="col-12 hp-form-info text-center mt-16">
                        <span class="text-black-80 hp-text-color-dark-40 hp-caption me-4">Got any issues?</span>
                        <a class="text-danger-1 hp-text-color-dark-danger-2 hp-caption" href="">Reach Support</a>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const rememberCheckbox = document.getElementById('remember');
                            const rememberLabel = document.querySelector('label[for="remember"]');

                            // Set initial state
                            updateLabelStyle();

                            // Update on change
                            rememberCheckbox.addEventListener('change', updateLabelStyle);

                            function updateLabelStyle() {
                                if (rememberCheckbox.checked) {
                                    rememberLabel.style.color = '#dc3545';
                                } else {
                                    rememberLabel.style.color = '';
                                }
                            }
                        });
                    </script>

                    <div class="mt-32 mt-sm-64 col-12">
                        <p class="hp-p1-body text-center hp-text-color-black-60 mb-8"> Copyright 2024 Tonight Group. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
