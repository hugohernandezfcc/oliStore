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
use App\Models\LineAnyItem;
use App\Models\WeekDay;
use App\Models\Sales;
use App\Models\tickets;


class LineAnyItemController extends Controller
{
    public $nativeRecord;
    public $lineAnyItemRecord;
    public $targetId;

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

    public function storeFromRelatedList(Request $request)
    {   
        $this->lineAnyItemRecord =  [
            'type'          => $request->origin . '_' . $request->table,
            'origin'        => $request->origin,
            'target'        => $request->table,
            'origin_id'     => $request->origin_field,
            'target_id'     => $request->target_field,
            'updated_by_id' => Auth::id(),
            'created_by_id' => Auth::id()
        ];


        if(!$request->justCreateRelationship){
            switch ($request->table) {
                case 'providers':
                    $this->nativeRecord = self::provider($request);
                    break;
            }
            $this->targetId = $this->nativeRecord->id;
        }else{
            $this->targetId = $request->target_id;
        }

        $this->lineAnyItemRecord[$request->origin_field] = $request->currentRecordId;
        $this->lineAnyItemRecord[$request->target_field] = $this->targetId;  

        
        return response()->json([
            (!$request->justCreateRelationship) ? $this->nativeRecord : self::callerFx($request->table, $this->targetId),
            LineAnyItem::create(
                $this->lineAnyItemRecord
            )
        ]);

    }

    public static function callerFx(String $table, Int $target_Id){
        switch ($table) {
            case 'products':
                return Product::find($target_Id);
                break;
            case 'providers':
                return Providers::find($target_Id);
                break;
            case 'week_days':
                return WeekDay::find($target_Id);
                break;
        }
    }

    public static function products(Request $request){
        return Product::create([
            'name'          => $request->name,
            'description'   => $request->Description,
            'price_list'    => $request->price_list,
            'created_by_id' => Auth::id(),
            'edited_by_id'  => Auth::id(),
            'store_id'      => $request->currentRecordId
        ]);
    }

    public static function provider(Request $request){
        return Providers::create([
            'representative' => $request->representative,
            'description'    => $request->description,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'whatsapp'       => $request->whatsapp,
            'company'        => $request->company,
            'visit_day'      => $request->visit_day,
            'created_by_id'  => Auth::id(),
            'edited_by_id'   => Auth::id(),
            'store_id'       => $request->currentRecordId
        ]);
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
    public function destroy(String $id)
    {
        LineAnyItem::where('id', $id)->delete();
        return response()->json([
            $id,
            'LineAnyItem eliminado'
        ]);
        
    }
}
