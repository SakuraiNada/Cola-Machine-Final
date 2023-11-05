<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{

    public function index()
    {
        $companies = Company::all();
        $products = Product::with('company')->get();
        return view('product_listindex', compact('products','companies'));
    }

    public function create()
    {
        $companies= Company::all();
        return view('product_new', compact('companies'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'product_name' => 'required|string',
            'company_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image',
        ]);

        $product = new Product();
        $product->product_name = $validatedData['product_name'];
        $product->company_id = $validatedData['company_id'];
        $product->price = $validatedData['price'];
        $product->stock = $validatedData['stock'];
        $product->comment = $validatedData['comment'];

        if ($request->hasFile('img_path')) {
            $imagePath = $request->file('img_path')->store('img_path', 'public');
            $product->img_path = $imagePath;
        }

        $product->save();

        return redirect()->route('product.registration.create');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id); 
        $companies = Company::all(); 
        return view('product.edit', compact('product', 'companies'));
    }

    public function update(Request $request, $id)
    {
        
        $validatedData = $request->validate([
            'product_name' => 'required|string',
            'company_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image', 
        ]);
    
        $product = Product::find($id);

        $product->product_name = $validatedData['product_name'];
        $product->company_id = $validatedData['company_id'];
        $product->price = $validatedData['price'];
        $product->stock = $validatedData['stock'];
        $product->comment = $validatedData['comment'];
    
        if ($request->hasFile('img_path')) {

            $imagePath = $request->file('img_path')->store('img_path', 'public');
            $product->img_path = $imagePath;
        }
    
        $product->save();
    
        return redirect()->route('product.show', ['id' => $product->id]);
    }
    
    public function destroy($id)
    {
        
            $product = Product::find($id);
            $product->delete();
    
            return redirect()->route('product.list');
    
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $company = $request->input('company');
        
        $products = Product::where('product_name', 'like', "%$keyword%")
            ->when($company, function ($query) use ($company) {
                return $query->where('company_id', $company);
            })
            ->get();
    
        $companies = Company::all();
    
        return view('product_listindex', compact('products', 'companies', 'keyword'));
    }
    
}




