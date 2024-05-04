<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\User;
use App\Models\Store;
use App\Models\Providers;
use App\Models\Stock;
use App\Models\ProductLineItem;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(){

        $stoks = Stock::get();
        $products2 = array();

        for ($i=0; $i < count($stoks); $i++) { 
            array_push($products2, $stoks[$i]->product_id);
        }

        $products = DB::table('products')
                    ->whereNotIn('id', $products2)
                    ->get();

        $products3 = Product::get();
        
        return Inertia::render('Stock/Index', [
            'stocks' => $stoks,
            'stockToCover' => count($products),
            'stockCovered' => count($stoks),
            'totalProducts' => count($products3),
            'porcentajeTotal' => number_format((count($stoks)*100)/count($products3), 1, '.', ''),
            'porcentajeFaltante' => 100-number_format((count($stoks)*100)/count($products3), 1, '.', '')
        ]);
    }

    public function create(){
        return Inertia::render('Stock/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        

        $Stock = Stock::updateOrCreate(
            ['folio' => $request->get('folio'), 'product_id' => $request->get('id')],
            [
                'name' => $request->get('name'),
                'folio' => $request->get('folio'),
                'description' => $request->get('Description'),
                'quantity' => intval($request->get('counterProducts')),
                'investment' => intval($request->get('counterProducts'))*floatval($request->get('price_list')),
                'profit' => (intval($request->get('counterProducts'))*floatval($request->get('price_list')))*0.30,
                'provider_id' => 2,
                'store_id' => 3,
                'product_id' => $request->get('id'),
                'created_by_id' => Auth::id(),
                'edited_by_id' => Auth::id()
            ]
        );

        return response()->json($Stock);
    }

    public function storeProduct(Request $request)  {
        $request->merge([
            'created_by_id' => Auth::id(),
            'edited_by_id' => Auth::id()
        ]);

        $product = Product::create($request->all());
        return response()->json($product);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
