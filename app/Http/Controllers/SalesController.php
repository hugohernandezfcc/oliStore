<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use App\Models\ProductLineItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Sales = Sales::whereDay('created_at', now()->day)->get();
        $summaryToday = new \stdClass();
        $summaryToday->total = 0;
        $summaryToday->no_products = 0;
        for ($i=0; $i < count($Sales) ; $i++) { 
            $summaryToday->total = $summaryToday->total+ $Sales[$i]->total;
            $summaryToday->no_products = $summaryToday->no_products + $Sales[$i]->no_products;
        }
        $yesterday = now()->day - 1;
        $SalesYesterday = Sales::whereDay('created_at', $yesterday)->get();
        $summaryYesterday = new \stdClass();
        $summaryYesterday->total = 0;
        $summaryYesterday->no_products = 0;
        for ($i=0; $i < count($SalesYesterday) ; $i++) { 
            $summaryYesterday->total = $summaryYesterday->total+ $SalesYesterday[$i]->total;
            $summaryYesterday->no_products = $summaryYesterday->no_products + $SalesYesterday[$i]->no_products;
        }

        

        $products = Product::get(['name', 'folio', 'Description']);
        for ($i=0; $i < $products->count(); $i++) { 
            $products[$i]->price_list   = '$' . $products[$i]->price_list . ' MXN'; 
            $products[$i]->price_customer   = '$' . $products[$i]->price_customer . ' MXN'; 
            $products[$i]->profit_percentage    = $products[$i]->profit_percentage . ' %'; 
        }

        
        return Inertia::render('Sales/Index', [
            'Sales' => $Sales,
            'SalesYesterday' => $SalesYesterday,
            'Sale' => [],
            'results' => $summaryToday,
            'resultsYesterday' => $summaryYesterday,
            'productFilters' => $products
        ]);
    }
    
    public function retrieveProduct(String $folio){
        return Product::where('folio', $folio)->get();
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
 
        $sale = Sales::create([
            'payment_method' => $request->get('payment_method'),
            'delivery_method' => $request->get('delivery_method'),
            'status' => $request->get('status'),
            'no_products' => $request->get('no_products'),
            'note' => $request->get('note'),
            'store' => $request->get('store'),
            'inbound_amount' => $request->get('inbound_amount'),
            'outbound_amount' => $request->get('outbound_amount'),
            'subtotal' => $request->get('total'),
            'total' => $request->get('total'),
            'closed' => true,
            'created_by_id' => Auth::id(),
            'edited_by_id' => Auth::id()
        ]);
        
        
        
        

        $prodLineRelacionados = $request->get('productosRelacionados');
        $prodLineRelacionadosArray = array();
        for ($i=0; $i < count($prodLineRelacionados); $i++) { 
            $ProductLineItem = ProductLineItem::create([
                'sale_id' => $sale->id,
                'product_id' => $prodLineRelacionados[$i]['id'],
                'take_portion' => $prodLineRelacionados[$i]['take_portion'],
                'unit_measure' => $prodLineRelacionados[$i]['unit_measure'],
                'quantity' => (isset($prodLineRelacionados[$i]['quantity'])) ? $prodLineRelacionados[$i]['quantity'] : 1,
                'final_price' => $prodLineRelacionados[$i]['final_price'],
                'created_by_id' => Auth::id(),
                'edited_by_id' => Auth::id()
            ]);
            array_push($prodLineRelacionadosArray, $ProductLineItem);
        }

        try {
            return redirect()->route('sales.index');
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([$th, $request]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
