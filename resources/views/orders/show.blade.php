@extends('layouts.app')
@section('title', 'Commande #' . $order->id)

@section('content')

<div class="mb-4">
    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i> Retour aux commandes
    </a>
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h2 class="fw-bold mb-1">Commande #{{ $order->id }}</h2>
            <p class="text-muted mb-0">
                <i class="bi bi-calendar3 me-1"></i>
                Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
            </p>
        </div>
        <span class="badge bg-{{ $order->status_color }} px-3 py-2 fs-6">
            {{ $order->status_label }}
        </span>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent fw-bold">
                <i class="bi bi-box-seam me-2"></i> Articles commandés
            </div>
            <div class="card-body p-0">
                @foreach($order->items as $item)
                <div class="p-3 d-flex align-items-center gap-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    {{-- Image --}}
                    @if($item->product && $item->product->image)
                    <img src="{{ Storage::url($item->product->image) }}"
                        class="rounded" width="60" height="60"
                        style="object-fit:cover;" alt="">
                    @else
                    <div class="rounded bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:60px;height:60px;">
                        <i class="bi bi-image text-muted"></i>
                    </div>
                    @endif

                    <div class="flex-grow-1">
                        <div class="fw-medium">
                            @if($item->product)
                            <a href="{{ route('products.show', $item->product) }}"
                                class="text-decoration-none text-dark">
                                {{ $item->product->title }}
                            </a>
                            @else
                            <span class="text-muted">Produit supprimé</span>
                            @endif
                        </div>
                        <small class="text-muted">Prix unitaire : {{ number_format($item->price, 2) }} DT</small>
                    </div>

                    <div class="text-center" style="min-width:60px;">
                        <div class="text-muted small">Qté</div>
                        <div class="fw-bold">{{ $item->quantity }}</div>
                    </div>

                    <div class="text-end" style="min-width:90px;">
                        <div class="fw-bold text-primary">
                            {{ number_format($item->price * $item->quantity, 2) }} DT
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Récapitulatif</h6>
                <div class="d-flex justify-content-between text-muted mb-2">
                    <span>Sous-total</span>
                    <span>{{ number_format($order->total, 2) }} DT</span>
                </div>
                <div class="d-flex justify-content-between text-muted mb-3">
                    <span>Livraison</span>
                    <span class="text-success">Gratuite</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total</span>
                    <span class="text-primary">{{ number_format($order->total, 2) }} DT</span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Statut de la commande</h6>
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-{{ $order->status_color }} bg-opacity-15
                                d-flex align-items-center justify-content-center"
                        style="width:48px;height:48px;">
                        <i class="bi bi-{{ $order->status === 'validated' ? 'check-circle-fill' : ($order->status === 'cancelled' ? 'x-circle-fill' : 'clock-fill') }}
                                  text-{{ $order->status_color }} fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold">{{ $order->status_label }}</div>
                        <small class="text-muted">{{ $order->updated_at->diffForHumans() }}</small>
                    </div>
                </div>

                @if($order->status === 'pending')
                <hr>
                <form action="{{ route('orders.cancel', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-outline-danger w-100"
                        onclick="return confirm('Voulez-vous vraiment annuler cette commande ?')">
                        <i class="bi bi-x-circle me-1"></i> Annuler la commande
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection