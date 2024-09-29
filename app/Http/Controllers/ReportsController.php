<?php

namespace App\Http\Controllers;


use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrder;
use App\Models\Providers;
use App\Models\Store;
use App\Models\tickets;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use App\Models\Sales;
use App\Models\ProductLineItem;
use Illuminate\Http\JsonResponse;
use stdClass;
use Debugbar;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Reports/Index', [
            'reports' => Report::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return Inertia::render('Reports/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reporte = $request->all();
        $reporte['created_by_id'] = Auth::id();
        $reporte['updated_by_id'] = Auth::id();
        Report::create($reporte);
        return redirect()->route('reports.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reporte = Report::with('createdBy', 'editedBy')->find($id);
        $data = array();
        switch ($reporte->template) {
            case 'ReportSales':
                $data = self::reportSales($reporte, "inertia");
                break;
            case 'ReportPrices':
                $data = self::reportPrices("", "", $reporte, "inertia");
            break;

            case 'ReportQuantitySales':
                $data = self::reportQuantitySales("", "", $reporte, "inertia");
            break;

            case 'ReportQuantityRange':
                $data = self::reportQuantityRange($reporte, "inertia");
            break;

            default:
                # code...
                break;
        }

        return Inertia::render('Reports/' . $reporte->template, $data);
    }
    
    public static function returnStores($param){
        $where = [];
        $stores = [];
        $ids = [];
        $labels = [];

        if($param == 'all'){
            $where = Store::where('id', '!=', 0)->get();
        }else{
            $where = Store::where('id', '=', $param)->get();
        }

        foreach ($where as $store) {
            array_push($ids, $store->id);   
            array_push($labels, $store->name);   
            array_push($stores, [
                'value' => $store->id,
                'label' => $store->name
            ]);
        }
        return [ $stores, $ids, $labels ];
    }

    public static function reportQuantityExecution(Request $request): JsonResponse {

        $store = self::returnStores($request->store);
        $productLineItems = ProductLineItem::with([
            'saleId:id,created_at,store',
            'productId:id,name,price_list,price_customer,take_portion,Description',
            'createdBy:name'
        ])->whereBetween('created_at', [
            new Carbon($request->start),
            new Carbon($request->end)
        ])->orderBy('created_at', 'desc')->get();


        return response()->json([
            'start' => $request->start,
            'end' => $request->end,
            'store' => $request->store,
            'unitType' => $request->unitType,
            'reportResults' => [
                'records' => self::prepareReportData($productLineItems, $request->unitType, $store[2]),
            ]
        ]);
    }

    public static function prepareReportData($productLineItems, $unitType, $stores){
        $toReturn = [];
        for ($i=0; $i < count($productLineItems); $i++) { 
            $record = new \stdClass();
            if(in_array($productLineItems[$i]->saleId->store, $stores)){
                $record->product = $productLineItems[$i]->productId->name . ' - ' . $productLineItems[$i]->productId->Description;
                $record->store = $productLineItems[$i]->saleId->store;
    
                if(!$productLineItems[$i]->productId->take_portion){
                        $record->quantity = 1;
                    }else{
                        $record->quantity = floatval(number_format((floatval($productLineItems[$i]->final_price)/$productLineItems[$i]->productId->price_customer)*1000, 2));
                    }
    
                if(!$productLineItems[$i]->productId->take_portion){
                    $record->price = floatval($productLineItems[$i]->productId->price_customer);
                    $record->priceList = floatval($productLineItems[$i]->productId->price_list);
                }else{
                    $record->price = floatval($productLineItems[$i]->final_price);
                    $diff = floatval($productLineItems[$i]->productId->price_customer) - floatval($productLineItems[$i]->productId->price_list);
                    $getDiff = ($diff / floatval($productLineItems[$i]->productId->price_customer)) * 100;
                    $record->priceList = floatval($productLineItems[$i]->final_price) - (floatval($productLineItems[$i]->final_price) * ($getDiff / 100));
                }
    
                $record->take_portion = $productLineItems[$i]->productId->take_portion;
                
                if($unitType == 'all'){
                    if(!isset($toReturn[$productLineItems[$i]->productId->id])){
    
                        $toReturn[$productLineItems[$i]->productId->id] = [];
                        array_push($toReturn[$productLineItems[$i]->productId->id], $record);
                    }else{
                        array_push($toReturn[$productLineItems[$i]->productId->id], $record);
                    }
    
                }else{
                    if($productLineItems[$i]->productId->take_portion == $unitType){

                        if(!isset($toReturn[$productLineItems[$i]->productId->id])){
        
                            $toReturn[$productLineItems[$i]->productId->id] = [];
                            array_push($toReturn[$productLineItems[$i]->productId->id], $record);
                        }else{
                            
                            array_push($toReturn[$productLineItems[$i]->productId->id], $record);
                        }
                    }
                }
            }
        }
        return $toReturn;
        
    }

    public static function reportQuantityRange( $report, String $returnType) : array | JsonResponse {

        $store = self::returnStores('all');

        $productLineItems = ProductLineItem::with([
            'saleId:id,created_at,store',
            'productId:id,name,price_list,price_customer,take_portion,Description',
            'createdBy:name'
        ])->whereBetween('created_at', [
            (Carbon::today())->addDays(-10),
            Carbon::today()
        ])->orderBy('created_at', 'desc')->get();


        $data = [
            'report' => $report,
            'filters' => [(Carbon::today())->addDays(-1),Carbon::today()],
            'reportResults' => [
                'records'       => self::prepareReportData($productLineItems, 'all', $store[2]),
            ],
            'storesBackEnd' => $store[0]
        ];

        switch ($returnType) {
            case 'json':
                return response()->json($data);
                break;
            case 'inertia':
                return $data;
                break;
        }
    }

    public  function runReport(String $startDateTime = '', String $endDateTime = '',  $report, String $returnType = '') : array | JsonResponse {
        
        $productLineItems = ProductLineItem::with([
            'saleId:id,created_at,store',
            'productId:id,name,price_list,price_customer,take_portion,Description',
            'createdBy:name'
        ])->whereBetween('created_at', [
            new Carbon($startDateTime),
            new Carbon($endDateTime)
        ])->whereIn('sale_id', [])->orderBy('created_at', 'desc')->get();

        $data = [
            'report' => $report,
            'reportResults' => [
                'records' => $toReturn,

            ]
        ];
        
        switch ($returnType) {
            case 'json':
                return response()->json($data);
                break;
            case 'inertia':
                return $data;
                break;
            
        }
    }

    /**
     * 
     * @param $report   
     * @param $returnType - String to return a json response or array to inertia
     * 
     * @return array | JsonResponse
     * 
     */
    public static function reportSales(Report $report, String $returnType) : array | JsonResponse {
        $start = Carbon::now();

        $data = [
            'report' => $report,
            'reportResults' => [
                '30' =>ProductLineItem::with([
                    'saleId:id,created_at,created_by_id,store',
                    'productId:id,name,Description',
                    'createdBy:name'
                ])->whereBetween('created_at', [Carbon::parse(Carbon::now()->subDays(31))->format('Y-m-d H:i:s'), Carbon::parse($start)->format('Y-m-d H:i:s')])->get(),
                '15' => [],
                '8' => []
            ]
        ];

        switch ($returnType) {
            case 'json':
                return response()->json($data);
                break;
            case 'inertia':
                return $data;
                break;
        }
    }
    
    /**
     * 
     * @param $startDateTime   
     * @param $endDateTime   
     * @param $returnType - String to return a json response or array to inertia
     * 
     * @return array | JsonResponse
     * 
     */
    public static function reportPrices(String $startDateTime = '', String $endDateTime = '',  $report, String $returnType = '') : array | JsonResponse {
        $startDateTime = ($startDateTime == '') ? Carbon::now()->subDays(9) : $startDateTime;
        $endDateTime = ($endDateTime == '') ? Carbon::now() : $endDateTime;
        $carbon = Carbon::now();
        $productLineItems = ProductLineItem::with([
            'saleId:id,created_at,store',
            'productId:id,name,price_list,price_customer,take_portion',
            'createdBy:name'
        ])->whereBetween('created_at', [
            $carbon->startOfWeek()->format("Y-m-d H:i"), 
            $carbon->endOfWeek()->format("Y-m-d H:i")
        ])->orderBy('created_at', 'desc')->get();

        $toReturn = [];
        
        for ($i=0; $i < count($productLineItems); $i++) { 
            $record = new stdClass();

            if($productLineItems[$i]->saleId->store != ''){
                if($productLineItems[$i]->productId->take_portion == false){
                    if($productLineItems[$i]->saleId->store == "Oli Store 1")
                        $productLineItems[$i]->saleId->store = "Oli Store 1 (Pedregal)";

                    if(!isset($toReturn[$productLineItems[$i]->saleId->store]))
                        $toReturn[$productLineItems[$i]->saleId->store] = [];


                    if(isset($toReturn[$productLineItems[$i]->saleId->store][explode(' ', $productLineItems[$i]->created_at)[0]])){
                        
                        $record->product = $productLineItems[$i]->productId;
                        $record->store = $productLineItems[$i]->saleId;

                        array_push($toReturn[$productLineItems[$i]->saleId->store][explode(' ', $productLineItems[$i]->created_at)[0]], $record);
                    }else{
                        $toReturn[$productLineItems[$i]->saleId->store][explode(' ', $productLineItems[$i]->created_at)[0]] = array();
                        $record->product = $productLineItems[$i]->productId;
                        $record->store = $productLineItems[$i]->saleId;

                        array_push($toReturn[$productLineItems[$i]->saleId->store][explode(' ', $productLineItems[$i]->created_at)[0]], $record);
                    }
                }
            }
        }

        $data = [
            'report' => $report,
            'reportResults' => [
                'records' => $toReturn,
            ]
        ];

        switch ($returnType) {
            case 'json':
                return response()->json($data);
                break;
            case 'inertia':
                return $data;
                break;
            
        }

    }

    public static function reportQuantitySales(String $startDateTime = '', String $endDateTime = '',  $report, String $returnType = '') : array | JsonResponse {
        $startDateTime = ($startDateTime == '') ? Carbon::now()->subDays(8) : $startDateTime;
        $endDateTime = ($endDateTime == '') ? Carbon::now() : $endDateTime;
        $carbon = Carbon::now();

        $productLineItems = ProductLineItem::with([
            'saleId:id,created_at,store',
            'productId:id,name,price_list,price_customer,take_portion,Description',
            'createdBy:name'
        ])->whereBetween('created_at', [
            $startDateTime->format("Y-m-d H:i"), 
            // $carbon->startOfWeek()->format("Y-m-d H:i"), 
            $carbon->endOfWeek()->format("Y-m-d H:i")
        ])->orderBy('created_at', 'desc')->get();

        $toReturn = array(
            'Oli Store 1 (Pedregal)' => [],
            'Oli Store 2 (Plazas)' => []
        );
        // dd($productLineItems);
        for ($i=0; $i < count($productLineItems); $i++) { 

            //Se crea el nodo de la fecha por cada tienda:

            $st     = $productLineItems[$i]->saleId->store;
            $dSale  = explode(' ', $productLineItems[$i]->created_at)[0];
            $prod   = $productLineItems[$i]->productId->id;

            if(!isset($toReturn[$st][$dSale][$prod]))
                $toReturn[$st][$dSale][$prod] = [];

            if(isset($toReturn[$st][$dSale][$prod])){
                $record = new \stdClass();
                $record->take_portion = $productLineItems[$i]->productId->take_portion;
                $record->product  = $productLineItems[$i]->productId->name . ' - ' . $productLineItems[$i]->productId->Description;
                if(!$productLineItems[$i]->productId->take_portion)
                    $record->quantity = 1;
                else{
                    $record->quantity = floatval(number_format((floatval($productLineItems[$i]->final_price)/$productLineItems[$i]->productId->price_customer)*1000, 2));
                }

                $record->plist    = floatval($productLineItems[$i]->productId->price_list);
                $record->clist    = floatval($productLineItems[$i]->productId->price_customer);
                $record->fprice   = floatval($productLineItems[$i]->final_price);
                
                array_push($toReturn[$st][$dSale][$prod], $record);

            }

           
         }

        $data = [
            'report' => $report,
            'reportResults' => [
                'records' => $toReturn,

            ]
        ];

        switch ($returnType) {
            case 'json':
                return response()->json($data);
                break;
            case 'inertia':
                return $data;
                break;
            
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
