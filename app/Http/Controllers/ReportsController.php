<?php

namespace App\Http\Controllers;


use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrder;
use App\Models\Providers;
use App\Models\Store;
use App\Models\tickets;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use App\Models\Sales;
use App\Models\ProductLineItem;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Reports/Index', [
            'reports' => Report::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return Inertia::render('Reports/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reporte = $request->all();
        $reporte['created_by_id'] = Auth::id();
        $reporte['updated_by_id'] = Auth::id();
        Report::create($reporte);
        return redirect()->route('reports.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $start = Carbon::now();

        return Inertia::render('Reports/Show', [
            'report' => Report::with('createdBy', 'editedBy')->find($id),
            'reportResults' => [
                '30' =>ProductLineItem::with([
                    'saleId:id,created_at,created_by_id',
                    'productId:id,name,Description',
                    'createdBy:name'
                ])->whereBetween('created_at', [Carbon::parse(Carbon::now()->subDays(30))->format('Y-m-d H:i:s'), Carbon::parse($start)->format('Y-m-d H:i:s')])->get(),
                '15' => ProductLineItem::with([
                    'saleId:id,created_at,created_by_id',
                    'productId:id,name,Description',
                    'createdBy:name'
                ])->whereBetween('created_at', [Carbon::parse(Carbon::now()->subDays(15))->format('Y-m-d H:i:s'), Carbon::parse($start)->format('Y-m-d H:i:s')])->get(),
                '8' => ProductLineItem::with([
                    'saleId:id,created_at,created_by_id',
                    'productId:id,name,Description',
                    'createdBy:name'
                ])->whereBetween('created_at', [Carbon::parse(Carbon::now()->subDays(8))->format('Y-m-d H:i:s'), Carbon::parse($start)->format('Y-m-d H:i:s')])->get()
            ]
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
