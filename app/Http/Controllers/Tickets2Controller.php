<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use App\Models\ticketItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;

class Tickets2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return Inertia::render('Tickets2/Index', [
            'tickets' => self::ticketsList(20)
        ]);
    }

    public function indexLoadAfter(){
        return response()->json(
            [
                'tickets' => self::ticketsList(200)
            ]
        );
    }

    public static function ticketsList(int $limit){
        $tickets = Tickets::with('createdBy', 'ticketItems', 'provider', 'editedBy', )->orderBy('created_at', 'desc')->limit($limit)->get();
        $toSum = array();
        $toSum1 = array();
        foreach ($tickets as $ticket) {
            array_push($toSum, $ticket->total);
            $ticket->createdByName = $ticket->createdBy->name; 
            $ticket->total = '$'.$ticket->total.' MXN';
            $ticket->createddate = Carbon::parse($ticket->created_at, 'CST')->addHour(-6)->format('d-m-Y H:i');
            $ticket->date_time_issued = Carbon::parse($ticket->date_time_issued, 'CST')->addHour(-6)->format('d-m-Y H:i');
            $ticket->noProducts = ($ticket->ticketItems == null) ? 0 : count($ticket->ticketItems);
            if($ticket->noProducts > 0){
                for ($i=0; $i < count($ticket->ticketItems); $i++) { 

                    try {

                        

                        $ticket->ticketItems[$i]->unitCost = '$ '.strval(number_format(floatval($ticket->ticketItems[$i]->cost_customer) / floatval($ticket->ticketItems[$i]->quantity), 2, '.', ',')) . ' MXN';
                        $ticket->ticketItems[$i]->cost_customer = '$ '.strval($ticket->ticketItems[$i]->cost_customer) . ' MXN';
                    } catch (\Throwable $th) {

                        $ticket->ticketItems[$i]->unitCost = 0;
                    }

                }
            }
        }
        return $tickets;
    }

    public function productList()
    {
        $products = Product::select('id', 
                                    'name', 
                                    'Description', 
                                    'folio', 
                                    'take_portion', 
                                    'price_customer', 
                                    'price_list'
                    )->where('visible_product', true)->limit(100)->get();
        return response()->json($products);
    }
    
    public function searchProductList(Request $productSearch){
        $products = Product::select(
            "id",
            "name",
            "folio",
            "Description",
            "price_list",
            "price_customer",
            "updated_at",
            "take_portion"
          )->where('visible_product', true)
            ->where('name', 'LIKE', '%'.strtoupper($productSearch->get('search')).'%')
            ->orWhere('Description', 'LIKE', '%' . strtoupper($productSearch->get('search')) . '%') 
            ->orWhere('folio', 'LIKE', '%' . strtoupper($productSearch->get('search')) . '%') 
            ->orderBy("updated_at", "desc")
            ->limit(1000)
            ->get();
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('Tickets2/Create', [
            'users' => DB::table('users')->where('is_active', true)->get(),
            'stores'   => DB::table('stores')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Tickets $tickets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tickets $tickets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tickets $tickets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tickets $tickets)
    {
        //
    }
}
