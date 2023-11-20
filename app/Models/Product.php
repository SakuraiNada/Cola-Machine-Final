<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'product_name',
        'company_id',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function createProductWithTransaction($data)
    {
        return \DB::transaction(function () use ($data) {
            return $this->create($data);
        });
    }

    public function updateProductWithTransaction($data)
    {
        return \DB::transaction(function () use ($data) {
            $this->update($data);
            return $this;
        });
    }

    public function deleteProductWithTransaction()
    {
        return \DB::transaction(function () {
            $this->delete();
        });
    }

}







