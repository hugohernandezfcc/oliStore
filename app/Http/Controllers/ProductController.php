<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductLineItem;
use App\Models\Sales;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Faker\Factory;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::select('id', 'name', 'folio', 'Description', 'price_list', 'price_customer', 'updated_at')->get();

        return Inertia::render('Products2/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Store masive records
     */
    public function storeMasive(){
        $filePath   = storage_path('app/csv10.csv');
        $file       = fopen($filePath, 'r');
        $header     = fgetcsv($file);
        $products   = [];

        while ($row = fgetcsv($file)) {
            $products[] = array_combine($header, $row);
        }

        fclose($file);
        // dd($products);

        $productos = array();

        for ($i=0; $i < count($products); $i++) { 

            $valor = 0;
            $valorFinal = 0;
             try {
                $valor = doubleval($products[$i]["GANANCIA"]) - doubleval($products[$i]["PRECIO DE LISTA"]);
                $valorFinal = ($valor / doubleval($products[$i]["GANANCIA"])) * 100;
             } catch (\Throwable $th) {
                $valorFinal = 11;
             }

            $producto = Product::updateOrCreate([
                'name' => $products[$i]["NOMBRE"],
                'folio' => $products[$i]["CODIGO DE BARRAS"],
                'Description' => $products[$i]["DESCRIPCION"] . ' // ' . $products[$i]["MARCA"],
                'unit_measure' => $products[$i]["UNIDAD DE MEDIDA"],
                'price_list' => doubleval($products[$i]["PRECIO DE LISTA"]),
                'price_customer' => doubleval($products[$i]["GANANCIA"]),
                'profit_percentage' => $valorFinal,
                'expiry_date' => Carbon::createFromFormat('d/m/Y', $products[$i]["FECHA DE CADUCIDAD"])->format('Y-m-d'),
                'created_by_id' => Auth::id(),
                'edited_by_id' => Auth::id()
            ]);

            // print_r($producto);
            array_push($productos, $producto);
        }

        return response()->json($productos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return Inertia::render('Products2/Create');
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $producto = $request->all();

        if(!isset($producto['folio'])){
            $faker = Factory::create();
            $producto['folio'] = $faker->ean13();
        }
        $valor = doubleval($producto["price_customer"]) - doubleval($producto["price_list"]);
        $valorFinal = ($valor / doubleval($producto["price_customer"])) * 100;

        
        $producto['profit_percentage'] = $valorFinal;
        $producto['created_by_id'] = Auth::id();
        $producto['edited_by_id'] = Auth::id();
        $producto['created_at'] = Carbon::now();
        $producto['updated_at'] = Carbon::now();
        $item = Product::create($producto);
        

        return Inertia::render('Products2/Show', [
            'customRecord' => Product::with('createdBy', 'editedBy', 'ProductLineItems', 'prices', 'stocks')->find($item->id)
        ] );
    }
    
    public function storeStock(Request $request){

        $Stock = Stock::updateOrCreate(
            ['folio' => $request->get('folio'), 'product_id' => $request->get('product_id'), 'store_id' => $request->get('store_id')],
            [
                'name' => $request->get('name'),
                'folio' => $request->get('folio'),
                'description' => $request->get('description'),
                'quantity' => intval($request->get('counterProducts')),
                'investment' => intval($request->get('counterProducts'))*floatval($request->get('price_list')),
                'store_id' => $request->get('store_id'),
                'product_id' => $request->get('product_id'),
                'created_by_id' => Auth::id(),
                'edited_by_id' => Auth::id()
            ]
        );


        return response()->json($Stock);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render('Products2/Show', [
            'customRecord' => Product::with('createdBy', 'editedBy', 'ProductLineItems', 'prices', 'stocks')->find($product->id)
        ] );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products2/Edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
 
        $producto = $request->all();
        $valor = doubleval($producto["price_customer"]) - doubleval($producto["price_list"]);
        $valorFinal = ($valor / doubleval($producto["price_customer"])) * 100;

        
        $producto['profit_percentage'] = $valorFinal;
        $producto['updated_at'] = Carbon::now();


        $product->update($producto);
        return Inertia::render('Products2/Show', [
            'customRecord' => Product::with('createdBy', 'editedBy', 'ProductLineItems', 'prices')->find($product->id)
        ] );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }


}
