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
use PhpParser\Node\Stmt\Echo_;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = tickets::with('createdBy', 'ticketItems')->orderBy('created_at', 'desc')->get();

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

        

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
            'total_investment' => number_format(array_sum($toSum), 2, '.', ','),
            'totalTickets' => count($tickets)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function validateTicket( String $noTicket)
    {
        return tickets::where('noTicket', $noTicket)->with('ticketItems')->first();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   

        if($request->get('id') == ''){
        
            $sale = null;
            $ticketCount = tickets::where('noTicket', $request->get('noTicket'))->get();

            if(count($ticketCount) == 0){
                $datetime = Carbon::parse($request->get('dateTimeIssued'), 'CST');
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
        }else{
            $ticket = tickets::where('id', $request->get('id'))->with('ticketItems')->first();
            return response()->json($ticket);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = tickets::where('id', $id)->with('createdBy', 'ticketItems')->orderBy('created_at', 'desc')->first();
        $start = new Carbon($ticket->created_at);
        $end = Carbon::now();
        $ticket->created_at2 = Carbon::parse($ticket->created_at, 'CST')->addHour(-6)->format('d-m-Y H:i');
        $ticket->updated_at2 = Carbon::parse($ticket->updated_at, 'CST')->addHour(-6)->format('d-m-Y H:i');
        $ticket->date_time_issued = Carbon::parse($ticket->date_time_issued, 'CST')->addHour(-6)->format('d-m-Y H:i');
        

        $pushProducts = array();
        $quantitytotal = array();
        for ($i=0; $i < count($ticket->ticketItems); $i++) {


            if($ticket->ticketItems[$i]->product_id != null)
                array_push($pushProducts,$ticket->ticketItems[$i]->product_id);

            try {

                    

                $ticket->ticketItems[$i]->unitCost = '$ '.strval(number_format(floatval($ticket->ticketItems[$i]->cost_customer) / floatval($ticket->ticketItems[$i]->quantity), 2, '.', ',')) . ' MXN';
                $ticket->ticketItems[$i]->cost_customer = '$ '.strval($ticket->ticketItems[$i]->cost_customer) . ' MXN';
            } catch (\Throwable $th) {

                $ticket->ticketItems[$i]->unitCost = 0;
            }

            array_push($quantitytotal, intval($ticket->ticketItems[$i]->quantity));
            $ticket->ticketItems[$i]->editQuantity = floatval($ticket->ticketItems[$i]->quantity);
            $ticket->ticketItems[$i]->editvendidas = floatval($ticket->ticketItems[$i]->quantity);
            $ticket->ticketItems[$i]->vendidas = 0;
        }
        $ticket->quantitytotal = array_sum($quantitytotal);
        $ticket->noProducts = ($ticket->ticketItems == null) ? 0 : count($ticket->ticketItems);



   
        return Inertia::render('Tickets/Show', [
            'ticket'    => $ticket,
            'products'  => $ticket->ticketItems,
            'sales'     => DB::table('product_line_items')
                            ->join('sales', 'product_line_items.sale_id', '=', 'sales.id')
                            ->join('products', 'product_line_items.product_id', '=', 'products.id')
                            ->whereBetween('product_line_items.created_at', [$start, $end])
                            ->whereIn('product_id', $pushProducts)
                            ->select('sales.*', 'product_line_items.*', 'products.*') 
                            ->get(),
            'salesDates'=> [Carbon::parse($start, 'CST')->addHour(-6)->format('d-m-Y'), Carbon::parse($end, 'CST')->addHour(-6)->format('d-m-Y')]
        ]);
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
