@extends('layouts.app')
@section('title', 'Réinitialiser le mot de passe')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">

        <div class="text-center mb-4">
            <h2 class="fw-bold">Nouveau mot de passe</h2>
            <p class="text-muted">Choisissez un nouveau mot de passe sécurisé.</p>
        </div>

        <div class="card p-4 shadow-sm">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label class="form-label fw-medium">Email</label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ $email ?? old('email') }}" required readonly>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Nouveau mot de passe</label>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required placeholder="Min. 8 caractères">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Confirmer</label>
                    <input type="password" name="password_confirmation"
                        class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-check-lg me-1"></i> Réinitialiser
                </button>
            </form>
        </div>

    </div>
</div>
@endsection