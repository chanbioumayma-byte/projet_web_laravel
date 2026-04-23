@extends('layouts.app')
@section('title', 'Mon profil')

@section('content')

<div class="row g-4">

    {{-- Infos personnelles --}}
    <div class="col-md-5">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3"
                        style="width:80px;height:80px;font-size:2rem;font-weight:700;color:white;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    <small class="text-muted">
                        Membre depuis {{ $user->created_at->format('M Y') }}
                    </small>
                </div>

                <div class="row text-center">
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-primary">{{ $products->count() }}</div>
                        <div class="text-muted small">Produits</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-success">{{ $orders->count() }}</div>
                        <div class="text-muted small">Commandes</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-warning">{{ $user->reviews->count() }}</div>
                        <div class="text-muted small">Avis</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modifier infos --}}
        <div class="card shadow-sm">
            <div class="card-header bg-transparent fw-bold">
                <i class="bi bi-person-gear me-2"></i> Modifier mes informations
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom</label>
                        <input type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Email</label>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Laissez vide pour garder le mot de passe actuel.
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Mot de passe actuel</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            placeholder="Requis pour changer le mot de passe">
                        @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nouveau mot de passe</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Min. 8 caractères">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Confirmer</label>
                        <input type="password" name="password_confirmation"
                            class="form-control" placeholder="Répéter le nouveau mot de passe">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-medium">
                        <i class="bi bi-check-circle me-1"></i> Sauvegarder
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Mes produits --}}
    <div class="col-md-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <span class="fw-bold">
                    <i class="bi bi-box-seam me-2"></i> Mes produits
                </span>
                <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i> Ajouter
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($products as $product)
                <div class="p-3 d-flex align-items-center gap-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    @if($product->image)
                    <img src="{{ Storage::url($product->image) }}"
                        class="rounded" width="50" height="50"
                        style="object-fit:cover;">
                    @else
                    <div class="rounded bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:50px;height:50px;">
                        <i class="bi bi-image text-muted"></i>
                    </div>
                    @endif
                    <div class="flex-grow-1">
                        <a href="{{ route('products.show', $product) }}"
                            class="fw-medium text-decoration-none text-dark">
                            {{ Str::limit($product->title, 35) }}
                        </a>
                        <div class="text-muted small">{{ $product->category->name }}</div>
                    </div>
                    <div class="fw-bold text-primary text-nowrap">
                        {{ number_format($product->price, 2) }} DT
                    </div>
                    <a href="{{ route('products.edit', $product) }}"
                        class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i>
                    </a>
                </div>
                @empty
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-box-seam" style="font-size:2rem;opacity:.3;"></i>
                    <p class="mt-2 mb-0">Vous n'avez pas encore de produits.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Dernières commandes --}}
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <span class="fw-bold">
                    <i class="bi bi-clock-history me-2"></i> Dernières commandes
                </span>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">Tout voir</a>
            </div>
            <div class="card-body p-0">
                @forelse($orders as $order)
                <div class="p-3 d-flex align-items-center gap-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="flex-grow-1">
                        <div class="fw-medium">Commande #{{ $order->id }}</div>
                        <small class="text-muted">{{ $order->created_at->format('d/m/Y') }}</small>
                    </div>
                    <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                    <div class="fw-bold text-primary">{{ number_format($order->total, 2) }} DT</div>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-dark">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
                @empty
                <div class="p-4 text-center text-muted">
                    <p class="mb-0">Aucune commande.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection