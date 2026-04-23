<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le panier
     */
    public function index()
    {
        $cart  = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Ajouter un produit au panier
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1|max:100',
        ]);

        $qty  = $request->quantity ?? 1;
        $cart = session()->get('cart', []);
        $id   = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = [
                'id'       => $product->id,
                'title'    => $product->title,
                'price'    => $product->price,
                'image'    => $product->image,
                'quantity' => $qty,
            ];
        }

        session()->put('cart', $cart);

        return redirect()
            ->back()
            ->with('success', "« {$product->title} » ajouté au panier !");
    }

    /**
     * Mettre à jour la quantité
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = (int) $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Quantité mise à jour !');
    }

    /**
     * Supprimer un produit du panier
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $title = $cart[$id]['title'];
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()
                ->route('cart.index')
                ->with('success', "« {$title} » retiré du panier.");
        }

        return redirect()->route('cart.index');
    }

    /**
     * Vider le panier
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()
            ->route('cart.index')
            ->with('success', 'Panier vidé.');
    }
}
