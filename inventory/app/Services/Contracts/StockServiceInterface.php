<?php

namespace App\Services\Contracts;

use App\Models\StockMovement;

/**
 * Contract for the Stock Management Service.
 *
 * Binding this interface to a concrete implementation via the service container
 * allows swapping implementations (e.g., for testing or future refactoring)
 * without changing any controller code.
 */
interface StockServiceInterface
{
    /**
     * Record a stock movement and atomically adjust the product inventory level.
     *
     * @param  array{product_id: int, type: string, quantity: int, reason: string}  $data
     * @param  int  $userId  The authenticated user performing the movement.
     * @throws \Illuminate\Validation\ValidationException  If stock is insufficient for an OUT movement.
     */
    public function recordMovement(array $data, int $userId): StockMovement;
}
