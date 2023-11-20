<?php

namespace App\Http\Controllers;
use Illuminate\Http\validate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB; 
use App\Http\Requests\ArticleRequest;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $model; 

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {

        $products = Product::all();
    $query = Product::with('company');
    
    $sortField = $request->input('sort', 'id');
    $sortOrder = $request->input('order', 'asc');

    
    if ($sortField === 'price') {
        $query->orderBy('price', $sortOrder);
    } elseif ($sortField === 'stock') {
        $query->orderBy('stock', $sortOrder);
    } else {
        $query->orderBy($sortField, $sortOrder);
    }

    
    $products = $query->get();
    $companies = Company::all();
    
    return view('product_listindex', compact('products', 'companies', 'sortField', 'sortOrder'));

}

    public function create()
    {
        $companies = Company::all();
        return view('product_new', compact('companies'));
    }

    public function store(ArticleRequest $request)
    {
        {
            \DB::beginTransaction();
        
            try {
                $validatedData = $request->validate([
                    'product_name' => 'required|string',
                    'company_id' => 'required|integer',
                    'price' => 'required|numeric',
                    'stock' => 'required|integer',
                    'comment' => 'nullable|string',
                    'img_path' => 'nullable|image',
                ]);
        
                if ($request->hasFile('img_path')) {
                    $imagePath = $request->file('img_path')->store('img_path', 'public');
                    $validatedData['img_path'] = $imagePath;
                }
        
                $product = $this->model->create($validatedData);
        
                \DB::commit();
        
                return redirect()->route('product.registration.create');
            } catch (\Exception $e) {
                \DB::rollback();

                return redirect()->back()->with('error', 'error');
            }
        }
    }

    public function show ($id)
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
        \DB::beginTransaction();
    
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
    
            if ($request->hasFile('img_path')) {
                $imagePath = $request->file('img_path')->store('img_path', 'public');
                $validatedData['img_path'] = $imagePath;
            }
    
            $product->update($validatedData);
    
            \DB::commit();
    
            return redirect()->route('product.show', ['id' => $product->id]);
        } catch (\Exception $e) {
            \DB::rollback();

            return redirect()->back()->with('error', 'error');
        }
    }

    public function destroyAsync($id)
{
    \DB::beginTransaction();

    try {

        Log::debug($id);

Product::destroy($id);
\DB::commit();
     } catch (\Exception $e) {
        \DB::rollback();
     }
    }

    public function search(Request $request)
{
    $products = Product::all();
    $keyword = $request->input('keyword');
    $company = $request->input('company');

    $query = Product::with('company')
        ->where(function ($query) use ($keyword) {
            if (!empty($keyword)) {
                $query->where('product_name', 'like', "%$keyword%");
            }
        })
        ->where(function ($query) use ($company) {
            if (!empty($company)) {
                $query->where('company_id', $company);
            }
        });

    $priceMin = $request->input('price_min');
    $priceMax = $request->input('price_max');
    $stockMin = $request->input('stock_min');
    $stockMax = $request->input('stock_max');

    if (!empty($priceMin) && !empty($priceMax)) {
        $query->whereBetween('price', [$priceMin, $priceMax]);
    }

    if (!empty($stockMin) && !empty($stockMax)) {
        $query->whereBetween('stock', [$stockMin, $stockMax]);
    }

    $sortField = $request->input('sort', 'id');
    $sortOrder = $request->input('order', 'asc');

    $products = $query->get();
    $companies = Company::all();
   

    return view('product_listindex', compact('products', 'companies', 'keyword', 'sortField', 'sortOrder'));

}

}








