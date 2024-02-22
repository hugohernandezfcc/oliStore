<?php

namespace App\Http\Controllers;

use App\Models\tickets;
use App\Models\ticketItems;
use App\Models\Sales;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductLineItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\DB;




class TicketsController extends Controller
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
        $sale = null;
        $ticketCount = tickets::where('noTicket', $request->get('noTicket'))->get();

        if(count($ticketCount) == 0){
            $datetime = Carbon::parse($request->get('dateTimeIssued'), 'CST')->addHour(-6);
            $sale = tickets::create([
                'noTicket'          => $request->get('noTicket'),
                'who_issued_ticket' => $request->get('whoIssuedTicket'),
                'provider'          => $request->get('provider'),
                'total'             => $request->get('total'),
                'date_time_issued'  => $datetime,
                'created_by_id' => Auth::id(),
                'edited_by_id' => Auth::id()
            ]);
        }else{
            $sale = $ticketCount[0];
        }
       

        $productList = array();
        $rows = $request->get('quantityItems');
        for ($i=0; $i < count($rows); $i++) { 
            array_push($productList, $rows[$i]['producto']);
            
        }
        $plis = DB::table('products')
                            ->whereIn('folio', $productList)
                            ->get();
        $plisKey = array();
        for ($i=0; $i < count($plis); $i++) {
            $plisKey[$plis[$i]->folio] = $plis[$i];
        }


        
        $items = array();
        for ($i=0; $i < count($rows); $i++) { 
            $tItem = new ticketItems;
            
            try {
                $tItem->product_id = $plisKey[$rows[$i]['producto']]->id;
                $tItem->product_name = $plisKey[$rows[$i]['producto']]->name;
            } catch (\Throwable $th) {
                debug($th);
            }  

            $tItem->created_by_id = Auth::id();  
            $tItem->edited_by_id = Auth::id();  
            $tItem->store_id = 3;  
            $tItem->ticket_id = $sale->id;
            $tItem->cost_customer = $rows[$i]['money'];
            $tItem->bar_code = $rows[$i]['producto'];
            $tItem->quantity = floatval($rows[$i]['quantity']);
            $tItem->save();
            
            array_push($items, $tItem);
        }
        return response()->json($sale);

    }

    /**
     * Display the specified resource.
     */
    public function show(tickets $tickets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tickets $tickets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tickets $tickets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tickets $tickets)
    {
        //
    }
}
