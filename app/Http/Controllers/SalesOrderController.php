<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Account;
use App\Models\Orpb2b;
use Carbon\Carbon;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('SalesOrders/Index', [
            'salesOrders' => SalesOrder::with(['createdBy', 'updatedBy', 'account'])->get()
        ]);
    }

    public function ordersBySingleAccount(String $accountId){
        $salesOrder = SalesOrder::with(['account'])->where('account_id', '=', $accountId)->get();

        return response()->json($salesOrder);
    }

    public function productsOrder(String $orderId){
        $orpb2b = Orpb2b::with(['productb2b'])->where('salesorder_id', '=', $orderId)->get();

        return response()->json($orpb2b);
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

        if($request->get('note') == ''){
            $request->merge(['note' => 'Orden de compra generada por ' . User::find(Auth::id())->name . ' el ' . date('Y-m-d H:i:s') . ' para la tienda ' . Account::find($request->get('account_id'))->name]);
        }else{
            $request->merge(['note' => $request->get('note') . ' - Orden de compra generada por ' . User::find(Auth::id())->name . ' el ' . date('Y-m-d H:i:s') . ' para la tienda ' . Account::find($request->get('account_id'))->name]);
        }

        $salesOrder = SalesOrder::updateOrCreate(
            [
                'id' => $request->get('id'),
            ],
            [
                'account_id'    => $request->get('account_id'),
                'status'        => $request->get('status'),
                'note'          => $request->get('note'),
                'created_by'    => Auth::id(),
                'updated_by'    => Auth::id()
            ]
        );

        return response()->json($salesOrder);
    }

    public function updateProductOrderStatus(String $field, String $orpb2bId)
    {
        $salesOrder = Orpb2b::find($orpb2bId);

        $salesOrder->edited_by_id = Auth::id();
        $salesOrder->updated_at = Carbon::now();
        $salesOrder->requested  = false;
        $salesOrder->verified   = false;
        $salesOrder->loaded     = false;
        $salesOrder->deliveried = false;


        $salesOrder[$field] = true;
        $salesOrder->save();

        return response()->json($salesOrder);

    }


    public function storeApp(Request $request)
    {
        $account = Account::with(['createdBy', 'editedBy', 'contacts', 'salesOrders'])->where('phone', '=', $request->get('whatsappNumber') )->first();
        $containsActiveOrder = false;
        $salesOrder = null;
        $message = '*TICKET*'. PHP_EOL;
        $message .= 'No. de orden: ' . PHP_EOL;

        for($i = 0; $i < count($account->salesOrders); $i++){
            if($account->salesOrders[$i]->status == 'Abierta'){
                $containsActiveOrder = true;
                $salesOrder = $account->salesOrders[$i];
            }
        }
        $pli = $request->get('orderProducts');
        if($containsActiveOrder){
            $salesOrder->updated_at = Carbon::now();
            $salesOrder->note = $request->get('note') . '//' . $salesOrder->note;
            $salesOrder->status = 'Abierta';
            $salesOrder->payment_method = $request->get('paymentMethod');
            $salesOrder->total = $request->get('total');
            $salesOrder->no_products = count($pli);

            $salesOrder->save();
        }else{
            $salesOrder = SalesOrder::create([
                'account_id'    => $account->id,
                'status'        => 'Abierta',
                'note'          => $request->get('note') . '//' . 'Orden de compra generada por cliente el ' . date('Y-m-d H:i:s') . ' para la tienda ' . $account->name,
                'created_by'    => 1,
                'updated_by'    => 1,
                'payment_method' => $request->get('paymentMethod'),
                'total'         => $request->get('total'),
                'no_products'   => count($pli)
            ]);
        }

        $message .= $salesOrder->id . PHP_EOL;
        $message .= ' - Pedido: ' . PHP_EOL;


            for($i = 0; $i < count($pli); $i++){
                $message .= $pli[$i]['quantity'] . ' -' . $pli[$i]['name'] . PHP_EOL;
                 Orpb2b::create([
                    'salesorder_id'  => $salesOrder->id,
                    'productb2b_id'  => $pli[$i]['id'],
                    'quantity'       => $pli[$i]['quantity'],
                    'price'          => $pli[$i]['price'],
                    'created_by_id'  => 1,
                    'edited_by_id'   => 1,
                    'requested'      => true,
                    'verified'       => false,
                    'loaded'         => false,
                    'delivered'      => false,
                    'delivery_date'  => Carbon::now()->addDays(1),
                    'name'           => $pli[$i]['name'] . ' - ' . $pli[$i]['description'],
                   // 'quantity'       => $pli[$i]['quantity'],
                    'image'          => $pli[$i]['image'],
                    'unit_price'     => floatval($pli[$i]['price']),
                    'subtotal_price' => floatval($pli[$i]['price']) * floatval(explode(' ', $pli[$i]['quantity'])[0]),
                ]);

            }


        $message .= ' - Total: ' . $request->get('total') . PHP_EOL;
        $message .= ' Muchas gracias por tu pedido.' . PHP_EOL;

        return response()->json([
            'salesOrder' => $salesOrder,
            'message' => $message
        ])->setStatusCode(200);

    }

    /**
     * Display the specified resource.
     */
    public function show(String $salesOrder)
    {



        $so = SalesOrder::with(['salesOrderItems', 'createdBy', 'updatedBy', 'account'])->find($salesOrder);
        $so->created_at = $so->created_at->format('Y-m-d H:i:s');
        $so->updated_at = $so->updated_at->format('Y-m-d H:i:s');

        dd($so);

        return Inertia::render('SalesOrders/Show', [
            'customRecord' => $so,
            'created_at' => $so->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $so->updated_at->format('Y-m-d H:i:s'),
            'delivery_date' => $so->updated_at->addDays(1)->format('Y-m-d'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesOrder $salesOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesOrder $salesOrder)
    {
        //
    }

    public function updateStatus(String $status, String $recordId)
    {
        $salesOrder = SalesOrder::find($recordId);
        $salesOrder->update([
            'status' => urldecode($status),
            'updated_by' => Auth::id(),
            'updated_at' => Carbon::now()
        ]);
        return response()->json($salesOrder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salesOrder = SalesOrder::with(['salesOrderItems', 'cases'])->find($id);
        $salesOrder->cases()->delete();
        $salesOrder->salesOrderItems()->delete();
        $salesOrder->delete();
        return response()->json([
            'message' => 'Sales Order deleted successfully',
            'salesOrder' => $salesOrder
        ])->setStatusCode(200);
    }
}
