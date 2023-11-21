<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\ProductLineItem;
use App\Models\Sales;

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

    public function storeMasive(){
        $filePath   = storage_path('app/csv7.csv');
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
             

            $producto = Product::updateOrCreate([
                'name' => $products[$i]["NOMBRE"],
                'folio' => $products[$i]["CODIGO DE BARRAS"],
                'Description' => $products[$i]["DESCRIPCION"] . ' // ' . $products[$i]["MARCA"],
                'unit_measure' => $products[$i]["UNIDAD DE MEDIDA"],
                'price_list' => doubleval($products[$i]["PRECIO DE LISTA"]),
                'price_customer' => doubleval($products[$i]["PRECIO SUGERIDO"]),
                'profit_percentage' => doubleval($products[$i]["GANANCIA"]),
                'expiry_date' => Carbon::createFromFormat('d/m/Y', $products[$i]["FECHA DE CADUCIDAD"])->format('Y-m-d'),
                'created_by_id' => Auth::id(),
                'edited_by_id' => Auth::id()
            ]);

            // print_r($producto);
            array_push($productos, $producto);
        }

        // dd($products);


        

        


        return response()->json($productos);
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
        

        $product->ProductLineItems = ProductLineItem::where('product_id', $product->id)->get();
        $product->createdByUser = User::find($product->created_by_id);
        $product->editedByUser = User::find($product->edited_by_id);
        $product->totalVentas = count($product->ProductLineItems);
        $product->totalPrecioCliente = 0;
        $product->totalPrecioList = 0;
        $product->salesList = array();

        for ($i=0; $i < count($product->ProductLineItems); $i++) { 
            $product->ProductLineItems[$i]->sale = Sales::where('id', $product->ProductLineItems[$i]->sale_id)->get()->toArray();
            $product->totalPrecioCliente = $product->totalPrecioCliente + floatval($product->price_customer);
            $product->totalPrecioList = $product->totalPrecioList + floatval($product->price_list);
        }       
        
        
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
            'name'          => 'required',
            'Description' => 'required',
            'unit_measure' => 'required',
            'price_list' => 'required',
            'price_customer' => 'required',
            'profit_percentage' => 'required',
            'expiry_date' => 'required'
        ]);
        
        $product->update($request->all());
        return redirect()->route('products.index');
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
