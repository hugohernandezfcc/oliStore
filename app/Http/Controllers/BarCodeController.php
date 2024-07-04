<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarCode;
use Inertia\Inertia;

class CategoryControllerBarCodeController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $barcodes = BarCode::all();
        return Inertia::render('BarCodes/Index', ['barCodes' => $barcodes]);
    }

    // Show the form for creating a new resource
    public function create()
    {
        return Inertia::render('BarCodes/Create');
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'bar_code' => 'required',
        ]);

        BarCode::create($request->all());
        return redirect()->route('barcodes.index')->with('success', 'BarCode created successfully.');
    }

    // Display the specified resource
    public function show(BarCode $barcode)
    {
        return Inertia::render('BarCodes/Show', ['barcode' => $barcode]);
    }

    // Show the form for editing the specified resource
    public function edit(BarCode $barcode)
    {
        return Inertia::render('BarCodes/Edit', ['barcode' => $barcode]);
    }

    // Update the specified resource in storage
    public function update(Request $request, BarCode $barcode)
    {
        $request->validate([
            'product_id' => 'required',
            'bar_code' => 'required',
        ]);

        $barcode->update($request->all());
        return redirect()->route('barcodes.index')->with('success', 'BarCode updated successfully.');
    }

    // Remove the specified resource from storage
    public function destroy(BarCode $barcode)
    {
        $barcode->delete();
        return redirect()->route('barcodes.index')->with('success', 'BarCode deleted successfully.');
    }
}
