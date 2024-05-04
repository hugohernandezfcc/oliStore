<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductLineItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Dotenv\Util\Str;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessSalesIntoStock;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Sales = Sales::whereDay('created_at', now()->day)->get();
        $summaryToday = new \stdClass();
        $summaryToday->total = 0;
        $summaryToday->no_products = 0;
        for ($i=0; $i < count($Sales) ; $i++) { 
            $summaryToday->total = $summaryToday->total+ $Sales[$i]->total;
            $summaryToday->no_products = $summaryToday->no_products + $Sales[$i]->no_products;
        }

        $products = Product::whereNotNull('Description')->where('visible_product', true)->get(['name', 'folio', 'Description', 'price_list','price_customer','profit_percentage']);
        for ($i=0; $i < $products->count(); $i++) { 
            $products[$i]->price_list   = '$' . $products[$i]->price_list . ' MXN'; 
            $products[$i]->price_customer   = '$' . $products[$i]->price_customer ; 
            $products[$i]->profit_percentage    = $products[$i]->profit_percentage . ' %'; 
        }

        
        return Inertia::render('Sales/Index', [
            'Sales' => $Sales,
            'Sale' => [],
            'results' => $summaryToday,
            'productFilters' => $products,
            'isAdmin' => User::find(Auth::id())->is_admin
        ]);
    }

    public function salesToday($start, $end){
        
        $sales = Sales::whereBetween('created_at', [Carbon::parse($start)->format('Y-m-d H:i:s'), Carbon::parse($end)->format('Y-m-d H:i:s')])->get();
        $products = Product::get(['name', 'id', 'folio', 'Description', 'price_list','price_customer','profit_percentage']);
        $toSearchInProducts = array();

        
        
        for ($i=0; $i < count($sales); $i++) { 
            $datetime = Carbon::parse($sales[$i]->created_at, 'CST')->addHour(-6);
            
            $sales[$i]->fcreacion = $datetime->day .'/'. $datetime->month.'/'.$datetime->year. ' - ' . $datetime->hour+6 . ':' . $datetime->minute; 
            array_push($toSearchInProducts, $sales[$i]->id);
        }

        $toDefineProductsById = null;
        for ($i=0; $i < count($products); $i++) { 
            $toDefineProductsById[$products[$i]->id] = $products[$i];
        }

        $plis = DB::table('product_line_items')
                            ->whereIn('sale_id', $toSearchInProducts)
                            ->get();

        for ($o=0; $o < count($plis); $o++) { 
            $plis[$o]->product = $toDefineProductsById[$plis[$o]->product_id];
        }



        $toReturn = array();
        $productCounts = 0;
        $salesCounts = 0;
        for ($i=0; $i < count($sales); $i++) { 
            $soldProducts = array();
            $toReturn[$sales[$i]->id] = $sales[$i];
            $salesCounts = $salesCounts + $sales[$i]->total;
            for ($o=0; $o < count($plis); $o++) { 
                if($sales[$i]->id == $plis[$o]->sale_id){
                    array_push($soldProducts, $plis[$o]);
                }
            }
            $toReturn[$sales[$i]->id]->soldProducts = $soldProducts;
            $toReturn[$sales[$i]->id]->numberSoldProducts = count($soldProducts);
            $productCounts = $productCounts + count($soldProducts);
        }

        $fcreacionValues = array();
        $duplicates = array();

        foreach ($toReturn as $sale) {
            $fcreacion = $sale['fcreacion'];
            if (isset($fcreacionValues[$fcreacion])) 
                array_push($duplicates, $sale['id']);
            else
                $fcreacionValues[$fcreacion] = true;

        }

        return Inertia::render('Sales/Today', [
            'ventas' => $toReturn,
            'ventasTotales' => count($toReturn),
            'salesCount' => count($sales),
            'productCounts' => $productCounts,
            'total' => $salesCounts,
            'period' => $start .' al '. $end,
            'duplicates' => array_values($duplicates)
        ]);
    }

    public function salesYesterday(){

        $sales = Sales::whereDate('created_at', Carbon::yesterday())->get();
        $products = Product::get(['name', 'id', 'folio', 'Description', 'price_list','price_customer','profit_percentage']);
        $toSearchInProducts = array();

        
        
        for ($i=0; $i < count($sales); $i++) { 
            $datetime = Carbon::parse($sales[$i]->created_at, 'CST')->addHour(-6);
            
            $sales[$i]->fcreacion = $datetime->day .'/'. $datetime->month.'/'.$datetime->year. ' - ' . $datetime->hour+6 . ':' . $datetime->minute; 
            array_push($toSearchInProducts, $sales[$i]->id);
        }

        $toDefineProductsById = null;
        for ($i=0; $i < count($products); $i++) { 
            $toDefineProductsById[$products[$i]->id] = $products[$i];
        }

        $plis = DB::table('product_line_items')
                            ->whereIn('sale_id', $toSearchInProducts)
                            ->get();

        for ($o=0; $o < count($plis); $o++) { 
            $plis[$o]->product = $toDefineProductsById[$plis[$o]->product_id];
        }

        $toReturn = array();
        $productCounts = 0;
        $salesCounts = 0;
        for ($i=0; $i < count($sales); $i++) { 
            $soldProducts = array();
            $toReturn[$sales[$i]->id] = $sales[$i];
            $salesCounts = $salesCounts + $sales[$i]->total;
            for ($o=0; $o < count($plis); $o++) { 
                if($sales[$i]->id == $plis[$o]->sale_id){
                    array_push($soldProducts, $plis[$o]);
                }
            }
            $toReturn[$sales[$i]->id]->soldProducts = $soldProducts;
            $toReturn[$sales[$i]->id]->numberSoldProducts = count($soldProducts);
            $productCounts = $productCounts + count($soldProducts);
        }

        return Inertia::render('Sales/Yesterday', [
            'ventas' => $toReturn,
            'ventasTotales' => count($toReturn),
            'salesCount' => count($sales),
            'productCounts' => $productCounts,
            'total' => $salesCounts
        ]);
    }
    
    public function retrieveProduct(String $folio){
        return Product::where('folio', $folio)->get();
    }

    public function retrieveProductForStock(String $folio){
        return Product::with('stocks')->where('folio', $folio)->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in products table.
     */
    public function storeProductFromPos(Request $request)
    {
 
        $producto = $request->all();
 
        if(strlen($producto['folio']) == 0){
            $faker = Factory::create();
            $producto['folio'] = $faker->ean13();
        }
        $producto['name'] = strtoupper($producto['name']);
        $producto['unit_measure'] = strtoupper($producto['unit_measure']);
        $producto['created_by_id'] = Auth::id();
        $producto['edited_by_id'] = Auth::id();
        $producto['expiry_date'] = Carbon::today();

        $toReturn = Product::create($producto);
        return response()->json($toReturn);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
 
        $sale = Sales::create([
            'payment_method' => $request->get('payment_method'),
            'delivery_method' => $request->get('delivery_method'),
            'status' => $request->get('status'),
            'no_products' => $request->get('no_products'),
            'note' => $request->get('note'),
            'store' => $request->get('store'),
            'inbound_amount' => $request->get('inbound_amount'),
            'outbound_amount' => $request->get('outbound_amount'),
            'subtotal' => $request->get('total'),
            'total' => $request->get('total'),
            'closed' => true,
            'created_by_id' => Auth::id(),
            'edited_by_id' => Auth::id()
        ]);
        
        

        $prodLineRelacionados = $request->get('productosRelacionados');
        $prodLineRelacionadosArray = array();
        for ($i=0; $i < count($prodLineRelacionados); $i++) { 
            $ProductLineItem = ProductLineItem::create([
                'sale_id' => $sale->id,
                'product_id' => $prodLineRelacionados[$i]['id'],
                'take_portion' => $prodLineRelacionados[$i]['take_portion'],
                'unit_measure' => $prodLineRelacionados[$i]['unit_measure'],
                'quantity' => (isset($prodLineRelacionados[$i]['quantity'])) ? $prodLineRelacionados[$i]['quantity'] : 1,
                'final_price' => $prodLineRelacionados[$i]['final_price'],
                'created_by_id' => Auth::id(),
                'edited_by_id' => Auth::id()
            ]);
            array_push($prodLineRelacionadosArray, $ProductLineItem);
        }

        ProcessSalesIntoStock::dispatch($sale->id);

        return response()->json([$sale, $prodLineRelacionadosArray]);

    }

    /**
     * Display the specified resource.
     */
    public function showById($id)
    {
        $sale = Sales::find($id);

        $sale->ProductLineItems = ProductLineItem::where('sale_id', $id)->get();
        $sale->createdByUser = User::find($sale->created_by_id);
        $sale->editedByUser = User::find($sale->edited_by_id);

        for ($i=0; $i < count($sale->ProductLineItems); $i++) { 
            $sale->ProductLineItems[$i]->product = Product::find($sale->ProductLineItems[$i]->product_id);
            
        }

        return Inertia::render('Sales/Show', compact('sale'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Sales $sales)
    {
        // $sale = Sales::find($sales->id);

        // $sale->ProductLineItems = ProductLineItem::where('sale_id', $sale->id)->get();
        // $sale->createdByUser    = User::find($sale->created_by_id);
        // $sale->editedByUser     = User::find($sale->edited_by_id);

        // $sale->ProductLineItems = ProductLineItem::where('sale_id', $sale->id)->get();

        // for ($i=0; $i < count($sale->ProductLineItems); $i++) { 
        //     $sale->ProductLineItems[$i]->product = Product::where('id', $sale->ProductLineItems[$i]->product_id)->get()->toArray();
            
        // }


        // return Inertia::render('Sales/Show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function deleteSales($salesId)
    {
        try {
            ProductLineItem::where('sale_id', $salesId)->delete();
            Sales::find($salesId)->delete();
            return response()->json(['status' => 'success', 'message' => 'Venta eliminada correctamente', 'id' => $salesId]);
        } catch (\Exception $th) {
            return response()->json(['status' => 'error', 'message' => 'Error al eliminar la venta', 'id' => $salesId, 'error_detail' => $th->getMessage()]);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
