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
use App\Models\Financial;

class FinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $financials = Financial::with('store')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Financials/Index', [
            'financials' => $financials
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Financials/Create', [
            'customRecord' => null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $financial = new Financial();
        $financial->name = $request->name;
        $financial->description = $request->description;
        $financial->created_by_id = Auth::id();
        $financial->updated_by_id = Auth::id();
        $financial->store_id = $request->store_id;
        $financial->current = $request->current;


        $financial->save();

        return redirect()->route('financials.index'); 

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Financials/Show', [
            'customRecord' => Financial::with('store')->with('createdBy')->with('editedBy')->find($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Financials/Edit', [
            'customRecord' => Financial::with('store')->with('createdBy')->with('editedBy')->find($id)
        ]);
    }

    /**form
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $financial = Financial::find($id);

        // Check if the record exists
        if (!$financial) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $financial->id = $id;
        $financial->name = $request->name;
        $financial->description = $request->description;
        $financial->created_by_id = Auth::id();
        $financial->updated_by_id = Auth::id();
        $financial->store_id = $request->store_id;
        $financial->current = $request->current;


        $financial->save();

        return redirect()->route('financials.index'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $financial = Financial::find($id);
        $financial->delete();

        return redirect()->route('financials.index'); 
    }
}
