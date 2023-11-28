<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Product;
use App\Models\ProductLineItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class SalesProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return Inertia::render('SalesProducts/Index', [
            'Sales' => Sales::get(),
            'isAdmin' => (Auth::id() == 1) ? true : false
        ]);
    }

    public function editSales(){
        $products = Sales::get();

        for ($i=0; $i < count($products); $i++) { 
            $products[$i]->created_at = Carbon::yesterday();
            $products[$i]->save(['timestamps' => false]);
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sales $sales)
    {
        
        $sales->ProductLineItems = ProductLineItem::where('sale_id', $sales->id)->get();
        $sales->createdByUser = User::find($sales->created_by_id);
        $sales->editedByUser = User::find($sales->edited_by_id);
        $sales->totalVentas = count($sales->ProductLineItems);
        $sales->totalPrecioCliente = 0;
        $sales->totalPrecioList = 0;
        $sales->salesList = array();

        for ($i=0; $i < count($sales->ProductLineItems); $i++) { 
            $sales->ProductLineItems[$i]->sale = Sales::where('id', $sales->ProductLineItems[$i]->sale_id)->get()->toArray();
            $sales->totalPrecioCliente = $sales->totalPrecioCliente + floatval($sales->price_customer);
            $sales->totalPrecioList = $sales->totalPrecioList + floatval($sales->price_list);
        } 

        return Inertia::render('SalesProducts/Show', compact('sales'));
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
