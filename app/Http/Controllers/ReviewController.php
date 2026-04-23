<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Ajouter ou mettre à jour un avis
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            [
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
            ],
            [
                'rating'  => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Votre évaluation a été enregistrée !');
    }

    /**
     * Supprimer son propre avis
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        $review->delete();

        return redirect()
            ->back()
            ->with('success', 'Évaluation supprimée.');
    }
}
