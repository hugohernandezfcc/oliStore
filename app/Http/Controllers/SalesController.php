<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
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
        $Sales = Sales::get();

        return Inertia::render('Sales/Index', [
            'Sales' => $Sales,
            'Sale' => []
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

        $request->get('payment_method')


        return response()->json([$sale]);
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
