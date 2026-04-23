<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    /**
     * Seul le propriétaire peut modifier son produit
     */
    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

    /**
     * Seul le propriétaire peut supprimer son produit
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }
}
