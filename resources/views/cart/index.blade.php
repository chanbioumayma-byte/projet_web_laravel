@extends('layouts.app')
@section('title', 'Mon panier')

@section('content')

<h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Mon panier</h2>

@if(empty(session('cart')))

<div class="text-center py-5">
    <div class="mb-4" style="font-size:5rem;opacity:.25;">🛒</div>
    <h4 class="text-muted">Votre panier est vide</h4>
    <p class="text-muted mb-4">Découvrez nos produits et ajoutez-en à votre panier !</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-grid-3x3-gap me-2"></i> Voir le catalogue
    </a>
</div>

@else

<div class="row g-4">

    {{-- Produits --}}
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                @foreach(session('cart') as $id => $item)
                <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="row align-items-center g-3">

                        {{-- Image --}}
                        <div class="col-auto">
                            @if($item['image'])
                            <img src="{{ Storage::url($item['image']) }}"
                                class="rounded-3" width="80" height="80"
                                style="object-fit:cover;" alt="{{ $item['title'] }}">
                            @else
                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center"
                                style="width:80px;height:80px;">
                                <i class="bi bi-image text-muted fs-4"></i>
                            </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="col">
                            <h6 class="fw-semibold mb-1">{{ $item['title'] }}</h6>
                            <div class="text-primary fw-medium">{{ number_format($item['price'], 2) }} DT</div>
                        </div>

                        {{-- Quantité --}}
                        <div class="col-auto">
                            <form action="{{ route('cart.update', $id) }}" method="POST"
                                class="d-flex align-items-center gap-2">
                                @csrf @method('PATCH')
                                <div class="input-group" style="width:110px;">
                                    <input type="number" name="quantity"
                                        value="{{ $item['quantity'] }}"
                                        min="1" max="99"
                                        class="form-control text-center fw-medium">
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-secondary"
                                    title="Mettre à jour">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Sous-total --}}
                        <div class="col-auto text-end">
                            <div class="fw-bold text-dark">
                                {{ number_format($item['price'] * $item['quantity'], 2) }} DT
                            </div>
                            <form action="{{ route('cart.remove', $id) }}" method="POST" class="mt-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-link btn-sm text-danger p-0 text-decoration-none">
                                    <i class="bi bi-trash me-1"></i> Retirer
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Continuer mes achats
                </a>
                <form action="{{ route('cart.clear') }}" method="POST"
                    onsubmit="return confirm('Vider le panier ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> Vider le panier
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Récapitulatif --}}
    <div class="col-lg-4">
        <div class="card shadow-sm sticky-top" style="top:80px;">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Récapitulatif</h5>

                @foreach(session('cart') as $item)
                <div class="d-flex justify-content-between text-muted small mb-2">
                    <span class="me-2">{{ Str::limit($item['title'], 25) }} × {{ $item['quantity'] }}</span>
                    <span class="text-nowrap fw-medium text-dark">
                        {{ number_format($item['price'] * $item['quantity'], 2) }} DT
                    </span>
                </div>
                @endforeach

                <hr>

                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Sous-total</span>
                    <span>{{ number_format($total, 2) }} DT</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Livraison</span>
                    <span class="text-success fw-medium">Gratuite</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                    <span>Total</span>
                    <span class="text-primary">{{ number_format($total, 2) }} DT</span>
                </div>

                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100 btn-lg fw-medium">
                        <i class="bi bi-check-circle me-2"></i> Passer la commande
                    </button>
                </form>

                <div class="mt-3 d-flex justify-content-center gap-3 text-muted small">
                    <span><i class="bi bi-shield-check me-1"></i> Paiement sécurisé</span>
                    <span><i class="bi bi-truck me-1"></i> Livraison gratuite</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endif

@endsection