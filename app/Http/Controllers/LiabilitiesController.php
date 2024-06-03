<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductLineItem;
use App\Models\Liabilities;
use App\Models\Sales;
use App\Models\Price;
use Illuminate\Support\Facades\Auth;


class LiabilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $liabilities = Liabilities::with('financial')->get();

        return Inertia::render('Liabilities/Index', [
            'liabilities' => $liabilities
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Liabilities/Create', [
            'customRecord' => null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $liabilitieslist = Liabilities::with('financial')->get();

        $Liabilities = new Liabilities();
        $Liabilities->name = $request->name;
        $Liabilities->description = $request->description;
        $Liabilities->created_by_id = Auth::id();
        $Liabilities->updated_by_id = Auth::id();
        $Liabilities->financial_id = $request->financial_id;
        $Liabilities->percentage = $request->percentage;

        $Liabilities->save();

        return redirect()->route('liabilities.index'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        return Inertia::render('Liabilities/Show', [
            'customRecord' => Liabilities::with('financial')->with('createdBy')->with('editedBy')->find($id)
        ]);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Liabilities/Edit', [
            'customRecord' => Liabilities::with('financial')->with('createdBy')->with('editedBy')->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $Liabilities = Liabilities::find($id);

        // Check if the record exists
        if (!$Liabilities) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $Liabilities->name = $request->name;
        $Liabilities->description = $request->description;
        $Liabilities->created_by_id = Auth::id();
        $Liabilities->updated_by_id = Auth::id();
        $Liabilities->financial_id = $request->financial_id;
        $Liabilities->percentage = $request->percentage;

        $Liabilities->save();
        return redirect()->route('liabilities.index');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Liabilities = Liabilities::find($id);
        $Liabilities->delete();

        return redirect()->route('liabilities.index');
    }
}
