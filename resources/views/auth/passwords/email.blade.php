@extends('layouts.app')
@section('title', 'Mot de passe oublié')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">

        <div class="text-center mb-4">
            <h2 class="fw-bold">Mot de passe oublié ?</h2>
            <p class="text-muted">Entrez votre email pour recevoir un lien de réinitialisation.</p>
        </div>

        @if (session('status'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
        </div>
        @endif

        <div class="card p-4 shadow-sm">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label fw-medium">Adresse email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input id="email" type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required autofocus>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-send me-1"></i> Envoyer le lien
                </button>
            </form>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-muted small">
                <i class="bi bi-arrow-left"></i> Retour à la connexion
            </a>
        </div>

    </div>
</div>
@endsection