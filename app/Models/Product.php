<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    // Define the fields that are mass assignable.
    protected $fillable = [
        'product_name',
        'company_id',
        'price',
        'stock',
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
    
}






