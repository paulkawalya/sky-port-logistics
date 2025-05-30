@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5 col-xl-4 py-5">
            <div class="text-center mb-4">
                <!-- <img src="{{ asset('app-assets/img/logo/tonightgroup.png') }}" alt="Logo" style="max-height: 50px;"> -->
                <img src="https://ik.imagekit.io/kawalya/gruland.png?updatedAt=1748587398384" alt="Logo" style="max-height: 100px;">
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-8">
                    <label for="email" class="form-label visually-hidden">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Email Address') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-8">
                    <label for="password" class="form-label visually-hidden">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        {{ __('Login') }}
                    </button>
                </div>
            </form>

            <div class="text-center mt-5">
                <p class="text-muted small">Copyright &copy; {{ date('Y') }} Gruland Logistics.</p>
            </div>
        </div>
    </div>
</div>
@endsection
