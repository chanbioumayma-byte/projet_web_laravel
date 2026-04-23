<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Historique des commandes
     */
    public function index()
    {
        $orders = Auth::user()
            ->orders()
            ->with('items.product')
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Détail d'une commande
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    /**
     * Passer la commande depuis le panier
     */
    public function store()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Votre panier est vide !');
        }

        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        $order = Order::create([
            'user_id' => Auth::id(),
            'status'  => 'pending',
            'total'   => $total,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);
        }

        session()->forget('cart');

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Commande passée avec succès ! Numéro : #' . $order->id);
    }

    /**
     * Annuler une commande
     */
    public function cancel(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'Seules les commandes en attente peuvent être annulées.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Commande #' . $order->id . ' annulée.');
    }
}
