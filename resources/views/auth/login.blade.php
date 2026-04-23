@extends('layouts.app')
@section('title', 'Connexion')

@section('content')
<div class="row justify-content-center min-vh-50 align-items-center">
    <div class="col-md-5 col-lg-4">

        <div class="text-center mb-4">
            <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                style="width:64px;height:64px;">
                <i class="bi bi-lock-fill text-white fs-3"></i>
            </div>
            <h2 class="fw-bold">Connexion</h2>
            <p class="text-muted">Bon retour sur ShopLaravel !</p>
        </div>

        <div class="card p-4 shadow-sm">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">Adresse email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input id="email" type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required autofocus
                            placeholder="votre@email.com">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label fw-medium mb-0">Mot de passe</label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-primary">
                            Mot de passe oublié ?
                        </a>
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required placeholder="••••••••">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-muted" for="remember">
                        Rester connecté
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-medium">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted mb-0">Pas encore de compte ?
                <a href="{{ route('register') }}" class="fw-medium">Créer un compte</a>
            </p>
        </div>

    </div>
</div>
@endsection