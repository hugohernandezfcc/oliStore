<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductLineItem;
use App\Models\Liabilities;
use App\Models\Sales;
use App\Models\Price;
use Illuminate\Support\Facades\Auth;
use App\Models\Financial;
use App\Models\Store;
use App\Models\Box;
use App\Models\BoxCut;

class BoxCutController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $caja = Box::where("store_id", $request->store_id)
                ->where("status", "open")
                ->whereDate("created_at", Carbon::today())
                ->where("seller_id", Auth::id())
                ->first();
        
        $corte = new BoxCut();
        $corte->store_id            = $request->store_id;
        $corte->cash_box            = $request->cash_box;
        $corte->cash_calculated     = $request->cash_calculated;
        $corte->cash_diff           = $request->cash_diff;
        $corte->cash_withdrawal     = $request->cash_withdrawal;
        $corte->name                = $request->name;
        $corte->withdrawal_expenses = $request->withdrawal_expenses;
        $corte->amount_released     = $request->amount_released;
        $corte->card_box            = $request->card_box;
        $corte->card_calculated     = $request->card_calculated;
        $corte->card_diff           = $request->card_diff;
        $corte->card_withdrawal     = $request->card_withdrawal;
        $corte->calculated          = $request->calculated;
        $corte->diff                = $request->diff;
        $corte->withdrawal          = $request->withdrawal;

        $corte->box_id              = $caja->id;
        $corte->seller_id           = Auth::id();
        $corte->created_by_id       = Auth::id();
        $corte->edited_by_id        = Auth::id();
        
        $corte->save();
        
        return response()->json([
            'message' => 'Corte de caja guardado',
            'corte' => $corte
        ]);

    }

    /**
     * Display the specified resource.
     * 
     * @param string $id - store_id
     */
    public function show(string $id)
    {
        $financial = Financial::with('store')->with('liabilities')->where('store_id', $id)->where('current', true)->orderBy('created_at', 'desc')->first();
        $dates = self::getBothDates();
        $store = Store::find($id);

        $sales = Sales::whereBetween('created_at', $dates['range'])->where('store', $store->name)->get();
        $cashCalculatedAmount = 0;
        $debitCalculatedAmount = 0;
        $calculatedDescounts = 0;
        
        foreach ($sales as $sale) {
            if($sale->payment_method == 'cash'){
                $cashCalculatedAmount += $sale->total;
            }else{
                $debitCalculatedAmount += $sale->total;
            }
        }

        foreach ($financial->liabilities as $f) {
            $calculatedDescounts += $f->percentage;
        }


        $caja = Box::where("store_id", $store->id)
                ->where("status", "open")
                ->whereDate("created_at", Carbon::today())
                ->where("seller_id", Auth::id())
                ->first();

        if($caja != null){
            $cashCalculatedAmount = ($cashCalculatedAmount + floatval($caja->amount))+ floatval($caja->founds_box);
        }

        $boxCut = BoxCut::where('store_id', $store->id)
            ->whereDate('created_at', Carbon::today())
            ->where('seller_id', Auth::id())
            ->orderBy('created_at', 'desc')  // Order by created_at in descending order
            ->get();

        $withdrawal = 0;
        foreach ($boxCut as $bc) {
            $withdrawal += $bc->withdrawal;
        }
        
        if($boxCut != null){
            $cashCalculatedAmount = $cashCalculatedAmount - $withdrawal;
        }


        return response()->json([
            'financial'             => $financial,
            'store'                 => $store,
            'sales'                 => $sales,
            'box'                   => $caja,
            'cashCalculatedAmount'      => $cashCalculatedAmount,
            'debitCalculatedAmount'      => $debitCalculatedAmount,
            'calculatedDescounts'   => $calculatedDescounts
        ]);
    }

    /**
     * @return 2 dates based on the request of index method to define the results.
     */
    private static function getBothDates(){
        
        $startDate    = Carbon::today();
        $endDate      = Carbon::tomorrow();
        
        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'range' =>[
                $startDate,
                $endDate
            ]
        ];
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
