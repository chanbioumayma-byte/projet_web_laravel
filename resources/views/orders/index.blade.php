@extends('layouts.app')
@section('title', 'Mes commandes')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-bag-check me-2"></i>Mes commandes</h2>
    <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-plus me-1"></i> Nouvelle commande
    </a>
</div>

@forelse($orders as $order)
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-2">
                <div class="fw-bold text-muted small">Commande</div>
                <div class="fw-bold fs-5">#{{ $order->id }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Date</div>
                <div>{{ $order->created_at->format('d/m/Y') }}</div>
                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
            </div>
            <div class="col-md-2">
                <div class="text-muted small">Articles</div>
                <div>{{ $order->items->count() }} article(s)</div>
            </div>
            <div class="col-md-2">
                <div class="text-muted small">Total</div>
                <div class="fw-bold text-primary">{{ number_format($order->total, 2) }} DT</div>
            </div>
            <div class="col-md-2">
                <span class="badge bg-{{ $order->status_color }} px-3 py-2 fs-6">
                    {{ $order->status_label }}
                </span>
            </div>
            <div class="col-md-1 text-end">
                <a href="{{ route('orders.show', $order) }}"
                    class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-eye"></i>
                </a>
            </div>
        </div>

        {{-- Aperçu des produits --}}
        <div class="mt-3 pt-3 border-top d-flex flex-wrap gap-2 align-items-center">
            @foreach($order->items->take(3) as $item)
            <span class="badge bg-light text-dark border">
                {{ $item->product->title ?? 'Produit supprimé' }} × {{ $item->quantity }}
            </span>
            @endforeach
            @if($order->items->count() > 3)
            <span class="text-muted small">+ {{ $order->items->count() - 3 }} autre(s)</span>
            @endif

            @if($order->status === 'pending')
            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="ms-auto">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Annuler la commande #{{ $order->id }} ?')">
                    <i class="bi bi-x-circle me-1"></i> Annuler
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@empty
<div class="text-center py-5">
    <div class="mb-4" style="font-size:5rem;opacity:.2;">📦</div>
    <h4 class="text-muted">Aucune commande</h4>
    <p class="text-muted mb-4">Vous n'avez pas encore passé de commande.</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary">
        <i class="bi bi-shop me-1"></i> Commencer mes achats
    </a>
</div>
@endforelse

@endsection