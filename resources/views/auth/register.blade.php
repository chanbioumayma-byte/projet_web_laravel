@extends('layouts.app')
@section('title', 'Inscription')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

        <div class="text-center mb-4">
            <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                style="width:64px;height:64px;">
                <i class="bi bi-person-plus-fill text-white fs-3"></i>
            </div>
            <h2 class="fw-bold">Créer un compte</h2>
            <p class="text-muted">Rejoignez ShopLaravel dès maintenant</p>
        </div>

        <div class="card p-4 shadow-sm">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-medium">Nom complet</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input id="name" type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required autofocus
                            placeholder="Prénom Nom">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">Adresse email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input id="email" type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required
                            placeholder="votre@email.com">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-medium">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required placeholder="Min. 8 caractères">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-medium">Confirmer le mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="form-control" required placeholder="Répéter le mot de passe">
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-medium">
                    <i class="bi bi-check-circle me-1"></i> Créer mon compte
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted mb-0">Déjà inscrit ?
                <a href="{{ route('login') }}" class="fw-medium">Se connecter</a>
            </p>
        </div>

    </div>
</div>
@endsection