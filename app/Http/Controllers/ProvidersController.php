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


class ProvidersController extends Controller
{
    public function index(){
        $providers = Providers::get();

        return Inertia::render('Providers2/Index', [
            'providers' => $providers,
        ]);
    }

    public function create(){
        return Inertia::render('Providers2/Create');
    }

    public function scheduleProviders(){
        $LineAnyItem = LineAnyItem::where('origin', 'providers')->where('type', 'providers_week_days')->where('target', 'week_days')
                                    ->with('provider')
                                    ->with('weekDay')
                                    ->with('orders')
                                    ->get();
        $prepareStore = [];  
        foreach ($LineAnyItem as $item){
            array_push($prepareStore, $item->provider->id);
        }
        $providersArray = [];
        $prods = LineAnyItem::where("type", "stores_providers")
                            ->whereIn("provider_id", $prepareStore)
                            ->with("provider")
                            ->with("store")
                            ->get();

        $storeToReturn = [];
        foreach ($prods as $item) {
            $providersArray[$item->provider->id] = $item->store->id;
            $storeToReturn[$item->store->id] = $item->store->name;
        }
        

        $orders = [];                            
        foreach ($LineAnyItem as $item) {
            $order = new \stdClass();
            $order->provider = $item->provider;
            $order->weekday  = $item->weekDay->name;
            $order->type     = (strpos($item->provider->company, 'V.D.') == false || strpos($item->provider->company, 'E.')) ? 'Venta' : 'preventa';
            $order->company  = $item->provider->company;
            
            if(isset($providersArray[$item->provider->id]))
                $order->store = $providersArray[$item->provider->id];
            else
                $order->store = null;

            $order->order    = $item->orders;
            $order->status   = ($item->orders != null) ? (strpos($item->provider->company, 'V.D.') === false || strpos($item->provider->company, 'E.')) ? 'Entregado' : 'Solicitado' : 'Pendiente';
            array_push($orders, $order);
        }
    
        return response()->json([
            'orders' => $orders,
            'stores' => $storeToReturn
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'created_by_id' => Auth::id(),
            'edited_by_id' => Auth::id()
        ]);
        Providers::create($request->all());
        return Inertia::render('Providers/Index');
    }

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $provider = Providers::with('createdBy')
                                ->with('editedBy')
                                ->find($id);

        $provider->line_any_items = array_merge(
            LineAnyItem::where('type', 'providers_week_days')->where('provider_id', $provider->id)
                                ->with('updatedBy')
                                ->with('createdBy')
                                ->with('provider')
                                ->with('weekDay')
                                ->get()->toArray(),
            LineAnyItem::where('type', 'stores_providers')->where('provider_id', $provider->id)
                                ->with('updatedBy')
                                ->with('createdBy')
                                ->with('provider')
                                ->with('store')
                                ->get()->toArray()
        );
        // $tickets = tickets::where('provider_id', null)->where('provider', 'LIKE', '%'.$provider->company.'%')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Providers2/Show', [
            'customRecord'      => $provider,
            'relatedListConfig' => [
                'providers_week_days' => [
                    'title'          => 'Días de visita',
                    'titleModel'     => 'Nuevo día de visita',
                    'visibleColumns' => WeekDay::RELATED_LIST_COLUMNS,
                    'formFields'     => WeekDay::MODAL_FORM_FIELDS,
                    'origin'         => 'providers',
                    'origin_field'   => 'provider_id',
                    'table'          => 'week_days',
                    'target_field'   => 'week_day_id',
                    'currentRecordId'=> $provider->id,
                    'searchIn'       => 'name',
                    'secondLine'     => 'description',
                    'lastLine'       => 'created_at'
                ],
                'stores_providers' => [
                    'title'          => 'Tiendas',
                    'titleModel'     => 'Nueva relación de proveedor',
                    'visibleColumns' => Store::RELATED_LIST_COLUMNS,
                    'formFields'     => Store::MODAL_FORM_FIELDS,
                    'origin'         => 'providers',
                    'table'          => 'stores',
                    'origin_field'   => 'provider_id',
                    'target_field'   => 'store_id',
                    'currentRecordId'=> $provider->id,
                    'searchIn'       => 'name',
                    'secondLine'     => 'street',
                    'lastLine'       => 'workin_hours'
                ],
            ],
            'relatedList'            => []
        ]);
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $provider = Providers::with('createdBy')
                                ->with('editedBy')
                                ->find($id);
        return Inertia::render('Providers2/Edit', [
            'provider'      => $provider
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $provider = Providers::find($id);
        $provider->update($request->all());
        return Inertia::render('Providers2/Show', [
            'customRecord' => $provider
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $LineAnyItem = LineAnyItem::where('provider_id', $id)
                                    ->where('target_id', 'provider_id')
                                    ->where('target', 'providers')
                                    ->get();

        if($LineAnyItem->count() == 1){
            foreach ($LineAnyItem as $item) {
                $item->delete();
            }
        }

        $Providers = Providers::find($id);
        $Providers->delete();

        return redirect()->route('providers.index');
    }
}
