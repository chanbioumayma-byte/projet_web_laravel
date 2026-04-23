@extends('layouts.app')
@section('title', $product->title)

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Catalogue</a></li>
        <li class="breadcrumb-item">
            <a href="{{ route('products.index', ['category' => $product->category_id]) }}">
                {{ $product->category->name }}
            </a>
        </li>
        <li class="breadcrumb-item active">{{ Str::limit($product->title, 30) }}</li>
    </ol>
</nav>

<div class="row g-5 mb-5">
    {{-- Image --}}
    <div class="col-md-5">
        @if($product->image)
        <img src="{{ Storage::url($product->image) }}"
            class="img-fluid rounded-3 shadow w-100"
            style="max-height:420px;object-fit:cover;"
            alt="{{ $product->title }}">
        @else
        <div class="img-placeholder rounded-3" style="height:420px;border-radius:14px!important;">
            <i class="bi bi-image" style="font-size:5rem;"></i>
        </div>
        @endif
    </div>

    {{-- Infos --}}
    <div class="col-md-7">
        <span class="badge bg-light text-secondary border mb-2">{{ $product->category->name }}</span>
        <h1 class="fw-bold">{{ $product->title }}</h1>

        {{-- Étoiles --}}
        <div class="mb-3">
            @php $avg = $product->averageRating(); @endphp
            @for($i = 1; $i <= 5; $i++)
                <i class="bi bi-star{{ $i <= round($avg) ? '-fill' : '' }} stars fs-5"></i>
                @endfor
                <span class="text-muted ms-2">
                    {{ $avg > 0 ? number_format($avg, 1) . '/5' : 'Aucun avis' }}
                    ({{ $product->reviews->count() }} avis)
                </span>
        </div>

        <h2 class="text-primary fw-bold mb-3">
            {{ number_format($product->price, 2) }} <small class="fs-4">DT</small>
        </h2>

        <p class="text-muted lh-lg">{{ $product->description }}</p>

        <div class="d-flex align-items-center gap-2 mb-4 text-muted">
            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                style="width:38px;height:38px;">
                <i class="bi bi-person-fill text-primary"></i>
            </div>
            <div>
                <div class="fw-medium text-dark">{{ $product->user->name }}</div>
                <small>Publié {{ $product->created_at->diffForHumans() }}</small>
            </div>
        </div>

        {{-- Ajouter au panier --}}
        @auth
        @if(Auth::id() !== $product->user_id)
        <form action="{{ route('cart.add', $product) }}" method="POST"
            class="d-flex gap-2 align-items-center mb-4">
            @csrf
            <div class="input-group" style="max-width:130px;">
                <span class="input-group-text">Qté</span>
                <input type="number" name="quantity" value="1" min="1" max="99"
                    class="form-control text-center fw-medium">
            </div>
            <button type="submit" class="btn btn-primary btn-lg flex-grow-1 fw-medium">
                <i class="bi bi-cart-plus me-2"></i> Ajouter au panier
            </button>
        </form>
        @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i> C'est votre produit.
        </div>
        @endif
        @else
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg mb-4 fw-medium">
            <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter pour acheter
        </a>
        @endauth

        {{-- Actions propriétaire --}}
        @auth
        @if(Auth::id() === $product->user_id)
        <div class="d-flex gap-2">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-secondary">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>
            <form action="{{ route('products.destroy', $product) }}" method="POST"
                onsubmit="return confirm('Voulez-vous vraiment supprimer ce produit ?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash me-1"></i> Supprimer
                </button>
            </form>
        </div>
        @endif
        @endauth
    </div>
</div>

{{-- Section Avis --}}
<hr class="my-4">
<div class="row g-4">

    {{-- Liste des avis --}}
    <div class="col-md-7">
        <h4 class="fw-bold mb-4">
            <i class="bi bi-chat-quote me-2"></i>Avis clients
            <span class="badge bg-secondary ms-2">{{ $product->reviews->count() }}</span>
        </h4>

        @forelse($product->reviews as $review)
        <div class="card p-3 mb-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center fw-bold"
                        style="width:38px;height:38px;color:#64748b;">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-medium">{{ $review->user->name }}</div>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                        @endfor
                </div>
            </div>

            @if($review->comment)
            <p class="text-muted mb-2 ms-1">{{ $review->comment }}</p>
            @endif

            @auth
            @if(Auth::id() === $review->user_id)
            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="text-end">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-link btn-sm text-danger p-0"
                    onclick="return confirm('Supprimer cet avis ?')">
                    <i class="bi bi-trash me-1"></i> Supprimer mon avis
                </button>
            </form>
            @endif
            @endauth
        </div>
        @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-chat-square-text" style="font-size:3rem;opacity:.3;"></i>
            <p class="mt-3">Aucun avis pour ce produit.<br>Soyez le premier à donner votre avis !</p>
        </div>
        @endforelse
    </div>

    {{-- Formulaire avis --}}
    <div class="col-md-5">
        @auth
        @if(Auth::id() !== $product->user_id)
        <div class="card p-4 shadow-sm">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-pencil-square me-2"></i>
                {{ $userReview ? 'Modifier mon avis' : 'Laisser un avis' }}
            </h5>
            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-medium">Note</label>
                    <div class="d-flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rating"
                                id="r{{ $i }}" value="{{ $i }}"
                                {{ (old('rating', $userReview?->rating) == $i) ? 'checked' : '' }}>
                            <label class="form-check-label stars" for="r{{ $i }}">
                                {{ $i }}<i class="bi bi-star-fill ms-1"></i>
                            </label>
                    </div>
                    @endfor
                </div>
                @error('rating')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-medium">Commentaire (optionnel)</label>
            <textarea name="comment" class="form-control" rows="4"
                placeholder="Partagez votre expérience...">{{ old('comment', $userReview?->comment) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100 fw-medium">
            <i class="bi bi-send me-1"></i>
            {{ $userReview ? 'Mettre à jour' : 'Publier l\'avis' }}
        </button>
        </form>
    </div>
    @endif
    @else
    <div class="card p-4 text-center shadow-sm">
        <i class="bi bi-lock-fill text-muted" style="font-size:2.5rem;"></i>
        <p class="mt-3 text-muted">Connectez-vous pour laisser un avis.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
    </div>
    @endauth
</div>

</div>
@endsection