<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    
    protected $fillable = [
        'product_name',
        'company_id',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    public static $rules = [
        'product_name' => 'required|string',
        'company_id' => 'required|integer',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'comment' => 'nullable|string',
        'img_path' => 'nullable|image',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public static function createProductWithTransaction($data)
    {
        return DB::transaction(function () use ($data) {
            return self::create($data);
        });
    }

    public static function updateProductWithTransaction($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $product = self::find($id);
            $product->update($data);
            return $product;
        });
    }

    public static function deleteProductWithTransaction($id)
    {
        return DB::transaction(function () use ($id) {
            $product = self::find($id);
            $product->delete();
        });
    }
}


