<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB; 
use App\Http\Requests\ArticleRequest;

class ProductController extends Controller
{
    protected $model; 

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $companies = Company::all();
        $products = $this->model->with('company')->get();
        return view('product_listindex', compact('products', 'companies'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('product_new', compact('companies'));
    }

    public function store(ArticleRequest $request)
{
    DB::beginTransaction();

    try {
        $validatedData = $request->validate([
            'product_name' => 'required|string',
            'company_id' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image',
        ]);

        $product = new $this->model;
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

        DB::commit();

        return redirect()->route('product.registration.create');
    } catch (\Exception $e) {
        DB::rollback();
    }
}

    public function show($id)
    {
        $product = $this->model->findOrFail($id);
        return view('product.show', compact('product'));
    }

    public function edit($id)
    {
        $product = $this->model->findOrFail($id);
        $companies = Company::all();
        return view('product.edit', compact('product', 'companies'));
    }

    public function update(ArticleRequest $request, $id)
    {
        DB::beginTransaction();
    
        try {
            $validatedData = $request->validate([
                'product_name' => 'required|string',
                'company_id' => 'required|integer',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|string',
                'img_path' => 'nullable|image',
            ]);
    
            $product = $this->model->find($id);
    
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
    
            DB::commit();
    
            return redirect()->route('product.show', ['id' => $product->id]);
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
    
    public function destroy($id)
    {
        DB::beginTransaction();
    
        try {
            $product = $this->model->find($id);
            $product->delete();
    
            DB::commit();
    
            return redirect()->route('product.list');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }    

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $company = $request->input('company');

        $products = $this->model
            ->where('product_name', 'like', "%$keyword%")
            ->when($company, function ($query) use ($company) {
                return $query->where('company_id', $company);
            })
            ->get();

        $companies = Company::all();

        return view('product_listindex', compact('products', 'companies', 'keyword'));
    }
}





