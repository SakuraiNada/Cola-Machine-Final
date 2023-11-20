<?php

namespace App\Http\Controllers;
use Illuminate\Http\validate;
use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB; 
use App\Http\Requests\ArticleRequest;
use App\Http\Controllers\ProductController;

class SalesController extends Controller

{
    public function purchase(Request $request)
    {
        \DB::beginTransaction();
    
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
    
        $productId = $request->input('product_id');
        $product = Product::find($productId);
    
        if (!$product) {
            return response()->json(['商品なし']);
        }
    
        if ($product->stock <= 0) {
            return response()->json(['在庫なし']);
        }
    
        try {

            $existingSale = Sale::where('product_id', $productId)->first();
    
            if ($existingSale) {
 
                $existingSale->increment('quantity');
            } else {

                $newSale = new Sale();
                $newSale->product_id = $productId;
                $newSale->quantity = 1; 
                $newSale->save();
            }
    
            $product->decrement('stock');
    
            \DB::commit();
    
            return response()->json(['message' => '購入出来ました']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}