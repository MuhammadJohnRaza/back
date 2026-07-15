<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;

class StockMovementPolicy
{
    /**
     * Determine whether the user can view any stock movements.
     * Both Admin and Staff can view the full movement log.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view a specific stock movement.
     */
    public function view(User $user, StockMovement $stockMovement): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create a stock movement.
     * Both Admin and Staff roles are permitted to log stock adjustments.
     * Only the Admin role can create/edit/delete Products and Categories.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Stock movements are immutable audit records — no updates allowed.
     * In a real system, corrections are made via a compensating movement.
     */
    public function update(User $user, StockMovement $stockMovement): bool
    {
        return false;
    }

    /**
     * Stock movements are immutable audit records — deletion is not permitted.
     * This preserves the integrity of the inventory audit trail.
     */
    public function delete(User $user, StockMovement $stockMovement): bool
    {
        return false;
    }
}
