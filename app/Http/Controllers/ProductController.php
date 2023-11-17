<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::get();

        for ($i=0; $i < $products->count(); $i++) { 
            $products[$i]->price_list   = '$' . $products[$i]->price_list . ' MXN'; 
            $products[$i]->price_customer   = '$' . $products[$i]->price_customer . ' MXN'; 
            $products[$i]->profit_percentage    = $products[$i]->profit_percentage . ' %'; 
        }

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'Description' => 'required',
            'unit_measure' => 'required',
            'price_list' => 'required',
            'price_customer' => 'required',
            'profit_percentage' => 'required',
            'expiry_date' => 'required'
        ]);
        
        $request->merge([
            'created_by_id' => Auth::id(),
            'edited_by_id' => Auth::id()
        ]);

        Product::create($request->all());
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render('Products/Show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Products/Edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required'
        ]);
        
        $product->update($request->all());
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
