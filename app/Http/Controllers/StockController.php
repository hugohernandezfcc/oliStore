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
use App\Models\Sales;

class StockController extends Controller
{
    public function index(){


        return Inertia::render('Stock/Index', [
            'stocks' => Stock::get()
        ]);
    }

    public function create(){
        // return Inertia::render('Providers/Create');
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
