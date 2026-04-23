<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Catalogue avec recherche, filtre catégorie et tri
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'user', 'reviews']);

        // Recherche par mot-clé
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtre par prix
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Tri
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        $products   = $query->paginate(9);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Détail d'un produit
     */
    public function show(Product $product)
    {
        $product->load(['reviews.user', 'category', 'user']);
        $userReview = null;

        if (Auth::check()) {
            $userReview = $product->reviews()
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('products.show', compact('product', 'userReview'));
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'price'       => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produit ajouté avec succès !');
    }

    /**
     * Formulaire modification
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'price'       => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Produit mis à jour avec succès !');
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produit supprimé avec succès !');
    }
}
