<?php

namespace App\Http\Controllers;

use App\Models\PriceBookEntry;
use Illuminate\Http\Request;
use App\Models\PriceBook;
use App\Models\ProductB2B;
use App\Models\User;



class PriceBookEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(PriceBookEntry $priceBookEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceBookEntry $priceBookEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PriceBookEntry $priceBookEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $priceBookEntry = PriceBookEntry::find($id);
        if (!$priceBookEntry) {
            return response()->json([
                'message' => 'PriceBookEntry not found'
            ], 404);
        }
    
        $priceBookEntry->delete();
        return response()->json([
            'message' => 'PriceBookEntry deleted successfully'
        ]);
    }
}
