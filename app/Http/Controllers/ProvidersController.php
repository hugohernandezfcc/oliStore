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
use App\Models\ProductLineItem;
use App\Models\Sales;

class ProvidersController extends Controller
{
    public function index(){
        $tiendas = Providers::get();

        return Inertia::render('Providers/Index', [
            'providers' => $tiendas,
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
        $request->merge([
            'created_by_id' => Auth::id(),
            'edited_by_id' => Auth::id()
        ]);
        Providers::create($request->all());
        return Inertia::render('Providers/Index');
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
