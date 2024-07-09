<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\BoxCut;
use App\Models\Store;
use App\Models\Box;

class BoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getBox()
    {
        $usuario = User::find(Auth::id());
        $box = Box::with('seller')
                    ->with('createdBy')
                    ->with('editedBy')
                    ->with('store')
                        ->where('status', 'open')
                        ->where('seller_id', Auth::id() )
                        ->where('store_id', $usuario->store_id )
                    ->orderBy('created_at', 'desc')->get();

        if($box->first() == null){
            return response()->json(
                [
                    'message' => 'No hay caja abierta',
                    'requireOpenBox' => true
                ]
            );
        }else{
            return response()->json(
                [
                    'message' => 'Caja abierta',
                    'requireOpenBox' => false,
                    'box' => $box->first()
                ]
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $usuario = User::find(Auth::id());
        $tienda = Store::find($usuario->store_id);

        
        $box = new Box();
        $box->box_date          = Carbon::now();
        $box->name              = 'Caja de '.$usuario->name;
        $box->status            = 'open';
        $box->amount            = floatval($request->amount);
        $box->founds_box        = floatval($request->founds_box);
        $box->description       = 'Caja abierta por ' . $usuario->name . ' - ' . strval(Carbon::now()) . ' en la tienda ' . $tienda->name . ' MONTO TOTAL: $ '. strval(floatval($box->amount) + floatval($box->founds_box)) . ' MXN';
        $box->seller_id         = Auth::id();
        $box->created_by_id     = Auth::id();
        $box->edited_by_id      = Auth::id();
        $box->store_id          = $usuario->store_id;
        $box->save();



        return response()->json(
            [
                'message' => 'Caja abierta',
                'status' => 'open',
                'box' => $box
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Box $box)
    {
        $box = Box::with('seller')
                    ->with('createdBy')
                    ->with('editedBy')
                    ->with('store')
                    ->with('boxCut')
                    ->find($box->id);

        return Inertia::render('Box/Show', [
            'customRecord' => $box,
            'relatedList'            => [
                'box_cut' => [
                    'title'               => 'Cortes de caja',
                    'titleModel'          => 'Nueva relación de corte de caja',
                    'visibleColumns'      => BoxCut::RELATED_LIST_COLUMNS,
                    'formFields'          => BoxCut::MODAL_FORM_FIELDS,
                    'table'               => 'boxcut',
                    'origin'              => 'box',
                    'origin_field'        => 'store_id',
                    'currentRecordId'     => $box->id,
                    'showNewRecordButton' => false
                ]
            ]
        ] );
    }
    
    public function edit(Box $box)
    {
        $box = Box::with('seller')
                    ->with('createdBy')
                    ->with('editedBy')
                    ->with('store')
                    ->with('boxCut')
                    ->find($box->id);

        return Inertia::render('Box/Edit', [
            'customRecord' => $box,
            'relatedList'            => [
                'box_cut' => [
                    'title'               => 'Cortes de caja',
                    'titleModel'          => 'Nueva relación de corte de caja',
                    'visibleColumns'      => BoxCut::RELATED_LIST_COLUMNS,
                    'formFields'          => BoxCut::MODAL_FORM_FIELDS,
                    'table'               => 'boxcut',
                    'origin'              => 'box',
                    'origin_field'        => 'store_id',
                    'currentRecordId'     => $box->id,
                    'showNewRecordButton' => false
                ]
            ]
        ] );
    }

    public function updateStandard(Request $request, string $id){
        $box = Box::find($id);
        $box->amount        = floatval($request->amount);
        $box->status        = $request->status;
        $box->founds_box    = floatval($request->founds_box);
        $box->description   = $request->description;
        $box->edited_by_id  = Auth::id();
        $box->save();

        return response()->json(
            [
                'message' => 'Caja actualizada',
                'box' => $box
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = User::find(Auth::id());
        $tienda = Store::find($usuario->store_id);
        $box = Box::find($id);
        $box->status        = 'close';
        $box->amount        = floatval($request->amount);
        $box->founds_box    = floatval($request->founds_box);
        $box->description   = 'Caja cerrada por ' . Auth::user()->name . ' - ' . strval(Carbon::now()) . ' en la tienda ' . $tienda->name . ' MONTO TOTAL: $ '. strval(floatval($box->amount) + floatval($box->founds_box)) . ' MXN';
        $box->edited_by_id  = Auth::id();
        $box->save();

        return  response()->json(
            [
                'message' => 'Caja cerrada',
                'status' => 'close',
                'box' => $box
            ]
        );
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
