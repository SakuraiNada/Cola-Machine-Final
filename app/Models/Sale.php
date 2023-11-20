<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 
use App\Models\Product;


class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
    ];

public function purchaseWithTransaction($productId)
    {
        return DB::transaction(function () use ($productId) {
            $product = Product::lockForUpdate()->findOrFail($productId);

            $product->decrement('stock');

            Sale::create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);

            return '購入できました';
        });
    }
}




