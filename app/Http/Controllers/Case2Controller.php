<?php

namespace App\Http\Controllers;

use App\Models\Case2;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Productb2b;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\Auth;

class Case2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Cases/Index', [
            'cases' => Case2::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Cases/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $SalesOrder = SalesOrder::find($request->salesorder_id);
        
        try {
            $case2 = Case2::create([
                'subject'       => $request->subject,
                'description'   => $request->description,
                'status'        => $request->status,
                'priority'      => $request->priority,
                'productb2b_id' => $request->productb2b_id,
                'account_id'    => $SalesOrder->account_id,
                'salesorder_id' => $request->salesorder_id,
                'orpb2b_id'     => $request->orpb2b_id,
                'type'          => $request->type,
                'created_by_id' => Auth::id(),
                'edited_by_id'  => Auth::id()
            ]);
    
            return response()->json([
                'case2' => $case2,
                'message' => 'Case created successfully'
            ], 201);


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Case2 $case2)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Case2 $case2)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Case2 $case2)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Case2 $case2)
    {
        //
    }
}
