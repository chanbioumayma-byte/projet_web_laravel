@extends('layouts.app')
@section('title', 'Accueil')

@section('content')

{{-- Hero --}}
<div class="hero p-5 mb-5 text-center">
    <h1 class="display-5 fw-bold mb-3">
        🛍️ Bienvenue sur <span class="text-warning">ShopLaravel</span>
    </h1>
    <p class="lead text-light mb-4">
        Achetez, vendez et échangez en toute simplicité.
    </p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg fw-medium px-4">
            <i class="bi bi-grid-3x3-gap me-1"></i> Voir le catalogue
        </a>
        @guest
        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
            <i class="bi bi-person-plus me-1"></i> S'inscrire
        </a>
        @else
        <a href="{{ route('products.create') }}" class="btn btn-outline-light btn-lg px-4">
            <i class="bi bi-plus-lg me-1"></i> Vendre un produit
        </a>
        @endguest
    </div>
</div>

{{-- Catégories --}}
<div class="mb-5">
    <h4 class="fw-bold mb-3">🗂️ Catégories</h4>
    <div class="d-flex flex-wrap gap-2">
        @foreach($categories as $cat)
        <a href="{{ route('products.index', ['category' => $cat->id]) }}"
            class="btn btn-outline-secondary btn-sm rounded-pill">
            {{ $cat->name }}
            @if($cat->products_count > 0)
            <span class="badge bg-secondary ms-1">{{ $cat->products_count }}</span>
            @endif
        </a>
        @endforeach
    </div>
</div>

{{-- Derniers produits --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">🆕 Dernières annonces</h4>
    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">
        Voir tout <i class="bi bi-arrow-right ms-1"></i>
    </a>
</div>

<div class="row g-4">
    @forelse($products as $product)
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100">
            {{-- Image --}}
            @if($product->image)
            <img src="{{ Storage::url($product->image) }}"
                class="card-img-top" style="height:200px;object-fit:cover;"
                alt="{{ $product->title }}">
            @else
            <div class="img-placeholder card-img-top" style="height:200px;">
                <i class="bi bi-image"></i>
            </div>
            @endif

            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="badge bg-light text-secondary border">{{ $product->category->name }}</span>
                    {{-- Note --}}
                    @if($product->reviews->count() > 0)
                    <small class="stars">
                        <i class="bi bi-star-fill"></i>
                        {{ number_format($product->averageRating(), 1) }}
                    </small>
                    @endif
                </div>

                <h6 class="card-title mt-1 fw-semibold">{{ Str::limit($product->title, 40) }}</h6>
                <p class="card-text text-muted small flex-grow-1">
                    {{ Str::limit($product->description, 70) }}
                </p>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="fw-bold text-primary fs-5">
                        {{ number_format($product->price, 2) }} <small class="fs-6">DT</small>
                    </span>
                    <a href="{{ route('products.show', $product) }}"
                        class="btn btn-sm btn-primary">Voir</a>
                </div>

                <small class="text-muted mt-2">
                    <i class="bi bi-person-circle me-1"></i>{{ $product->user->name }}
                </small>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-inbox" style="font-size:4rem;"></i>
        <p class="mt-3">Aucun produit disponible pour l'instant.</p>
        @auth
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            Ajouter le premier produit
        </a>
        @endauth
    </div>
    @endforelse
</div>

@endsection