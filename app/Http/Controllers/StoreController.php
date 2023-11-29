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
use App\Models\Provider;
use App\Models\Stock;
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

        $store->createdByUser = User::find($store->created_by_id);
        $store->editedByUser = User::find($store->edited_by_id);
        $store->ownerUser = User::find($store->owner_id);

        
   
        return Inertia::render('Stores/Show', compact('store'));
    }

    public function edit(Sales $sales){

    }

    public function update(Request $request, Sales $sales){

    }

    public function destroy(Sales $sales){

    }

}
