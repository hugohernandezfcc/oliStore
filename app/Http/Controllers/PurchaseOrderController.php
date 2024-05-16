<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrder;
use App\Models\Providers;
use App\Models\Store;
use App\Models\tickets;
use Inertia\Inertia;
use Illuminate\Support\Carbon;

class PurchaseOrderController extends Controller
{
    public function index(){


        return Inertia::render('PurchaseOrders/Index', [
            'providers' => Providers::get(),
            'stores' => Store::get(),
        ]);
    }

    public function create(){
        return Inertia::render('Providers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dt = Carbon::now();
        
        $PurchaseOrder = PurchaseOrder::create(
            [
                'code' => $dt->year.$dt->month.$dt->day.$dt->hour,
                'status' => 'Open',
                'sub_status' => 'Pending',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]
        );
        return response()->json(
            $PurchaseOrder
        );
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $tiendas = Providers::get();

        return Inertia::render('PurchaseOrders/Index', [
            'providers' => $tiendas,
        ]);
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
