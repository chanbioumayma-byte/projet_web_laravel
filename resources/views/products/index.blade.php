@extends('layouts.app')
@section('title', 'Catalogue')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Catalogue</h2>
        <small class="text-muted">{{ $products->total() }} produit(s) trouvé(s)</small>
    </div>
    @auth
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Vendre un produit
    </a>
    @endauth
</div>

{{-- Filtres --}}
<div class="card p-3 mb-4 shadow-sm">
    <form method="GET" action="{{ route('products.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                        placeholder="Titre, description..."
                        value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Catégorie</label>
                <select name="category" class="form-select">
                    <option value="">Toutes</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Prix min (DT)</label>
                <input type="number" name="price_min" step="0.01" min="0"
                    class="form-control" placeholder="0"
                    value="{{ request('price_min') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Prix max (DT)</label>
                <input type="number" name="price_max" step="0.01" min="0"
                    class="form-control" placeholder="9999"
                    value="{{ request('price_max') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Trier par</label>
                <select name="sort" class="form-select">
                    <option value="">Plus récents</option>
                    <option value="price_asc" {{ request('sort')=='price_asc'  ? 'selected':'' }}>Prix ↑</option>
                    <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected':'' }}>Prix ↓</option>
                    <option value="oldest" {{ request('sort')=='oldest'     ? 'selected':'' }}>Plus anciens</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-dark w-100">
                    <i class="bi bi-funnel"></i>
                </button>
            </div>
        </div>
        @if(request()->hasAny(['search','category','price_min','price_max','sort']))
        <div class="mt-2">
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Réinitialiser les filtres
            </a>
        </div>
        @endif
    </form>
</div>

{{-- Grille de produits --}}
<div class="row g-4">
    @forelse($products as $product)
    <div class="col-sm-6 col-md-4">
        <div class="card h-100">
            @if($product->image)
            <img src="{{ Storage::url($product->image) }}"
                class="card-img-top" style="height:220px;object-fit:cover;"
                alt="{{ $product->title }}">
            @else
            <div class="img-placeholder card-img-top" style="height:220px;border-radius:14px 14px 0 0;">
                <i class="bi bi-image"></i>
            </div>
            @endif

            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between mb-1">
                    <span class="badge bg-light text-secondary border small">
                        {{ $product->category->name }}
                    </span>
                    @if($product->reviews->count() > 0)
                    <span class="stars small">
                        <i class="bi bi-star-fill"></i>
                        {{ number_format($product->averageRating(), 1) }}
                        <span class="text-muted">({{ $product->reviews->count() }})</span>
                    </span>
                    @endif
                </div>

                <h5 class="card-title fw-semibold mt-1">{{ Str::limit($product->title, 45) }}</h5>
                <p class="card-text text-muted small flex-grow-1">
                    {{ Str::limit($product->description, 100) }}
                </p>

                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary fs-4">
                            {{ number_format($product->price, 2) }}<small class="fs-6 ms-1">DT</small>
                        </span>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye me-1"></i> Détails
                        </a>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-person-circle me-1"></i>{{ $product->user->name }}
                        <span class="ms-2">
                            <i class="bi bi-clock me-1"></i>{{ $product->created_at->diffForHumans() }}
                        </span>
                    </small>
                </div>
            </div>

            @auth
            @if(Auth::id() === $product->user_id)
            <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3">
                <div class="d-flex gap-2">
                    <a href="{{ route('products.edit', $product) }}"
                        class="btn btn-sm btn-outline-secondary flex-fill">
                        <i class="bi bi-pencil me-1"></i> Modifier
                    </a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                        onsubmit="return confirm('Supprimer ce produit ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endif
            @endauth
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-search" style="font-size:4rem;opacity:.3;"></i>
        <h5 class="mt-3">Aucun produit trouvé</h5>
        <p>Essayez d'autres critères de recherche.</p>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
            Voir tous les produits
        </a>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($products->hasPages())
<div class="d-flex justify-content-center mt-5">
    {{ $products->withQueryString()->links() }}
</div>
@endif

@endsection