<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    /**
     * L'utilisateur ne peut voir que ses propres commandes
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * L'utilisateur ne peut modifier/annuler que ses propres commandes
     */
    public function update(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }
}
