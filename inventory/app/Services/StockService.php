<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Services\Contracts\StockServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService implements StockServiceInterface
{
    /**
     * Record a stock movement and adjust the product inventory level.
     *
     * @throws ValidationException
     */
    public function recordMovement(array $data, int $userId): StockMovement
    {
        return DB::transaction(function () use ($data, $userId) {
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);
            $quantity = (int) $data['quantity'];
            $type = $data['type'];

            // Check if there is enough stock for OUT movements
            if ($type === 'out' && $product->quantity < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => ["Insufficient stock. Current stock is {$product->quantity}, but requested to withdraw {$quantity}."],
                ]);
            }

            // Record the stock movement
            $movement = StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $userId,
                'type' => $type,
                'quantity' => $quantity,
                'reason' => $data['reason'],
            ]);

            // Adjust the product stock level
            if ($type === 'in') {
                $product->quantity += $quantity;
            } else {
                $product->quantity -= $quantity;
            }
            $product->save();

            return $movement;
        });
    }
}
