<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\User;
use App\Models\Store;
use App\Models\ProductLineItem;
use App\Models\Sales;
use App\Models\Providers;
use App\Models\Stock;
use App\Models\LineAnyItem;

class StoreController extends Controller
{
    public function index(){
        $tiendas = Store::get();

        return Inertia::render('Stores/Index', [
            'stores' => $tiendas,
        ]);
    }

    public function create(){
        return Inertia::render('Stores/Create');
    }

    public function store(Request $request){

        $request->validate([
            'name'  => 'required',
            'street'    => 'required',
            'postal_code'   => 'required',
            'state' => 'required',
            'town'  => 'required',
            'country'   => 'required',
            'external_number' => 'required',
            'workin_hours' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'website' => 'required',
            'no_providers' => 'required'
        ]);
        $request->merge([
            'created_by_id' => Auth::id(),
            'owner_id' => Auth::id(),
            'edited_by_id' => Auth::id()
        ]);

        $sale = Store::create($request->all());

        return redirect()->route('stores.index');
    }

    public function show(Store $store){

        $tienda = Store::with('createdBy')
            ->with('updatedBy')
            ->with('owner')
            ->find($store->id);

        $tienda->line_any_items = LineAnyItem::where('origin', 'stores')->where('store_id', $store->id)
                ->with('updatedBy')
                ->with('createdBy')

                ->with('provider')
                ->with('worker')
                ->with('sales')
                ->with('box')
                ->with('category')
                ->with('orders')
            ->get();

            

        return Inertia::render('Stores/Show',[
            'customRecord'      => $tienda,
            'relatedListConfig' => [
                'stores_providers' => [
                    'title'          => 'Proveedores',
                    'titleModel'     => 'Nueva relación de proveedor',
                    'visibleColumns' => Providers::RELATED_LIST_COLUMNS,
                    'formFields'     => Providers::MODAL_FORM_FIELDS,
                    'table'          => 'providers',
                    'origin'         => 'stores',
                    'origin_field'   => 'store_id',
                    'target_field'   => 'provider_id',
                    'currentRecordId' => $store->id
                ],
            ]
        ] );
    }

    public function edit(Sales $sales){

    }

    public function update(Request $request, Sales $sales){

    }

    public function destroy(Sales $sales){

    }

}
