<?php

namespace App\Http\Controllers;

use App\Models\tickets;
use App\Models\ticketItems;
use App\Models\Sales;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public $startDate;
    public $endDate;
    public $dates;
    public $init;
    public $typeView;
    public $sales;
    public $salesCounts = 0;
    public $toSearchInProducts = array();
    public $toTicketsWithIssues = array();
    public $products;
    public $plis;
    public $productCounts = 0;
    public $meses = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
    ];
    public $dias = [
        'Lunes',
        'Martes',
        'Miercoles',
        'Jueves',
        'Viernes',
        'Sabado',
        'Domingo'
    ];
    public $finalResult = [];
    public $finalResultWeek = [];
    public $tickets = [];
    public $previously = [];
    public $pasiveData = 0;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $this->init = $req->all();
        $this->typeView = (count($this->init) > 0) ? 'custom_view': 'standard_view';

        /**
         * Ejecución de funciones para recopilar datos globales en toda la clase.
         */
        $this->prepareSalesForDashboard();
        $this->prepareProductsSoldDashboard();
        $this->prepareSalesByMonth();
        $this->prepareSalesByWeek();
        $this->prepareTickets();
        $this->prepareTicketsWithIssues();
        
        return response()->json(
            [   
                'typeView' => $this->typeView,
                'salesToday' => [
                    'mount' => '$'. $this->getSalesTotal() . ' MXN',
                    'percentage' => 685
                ],
                'productsToday' => [
                    'mount' => '# '. $this->getProductsSoldDashboard(),
                    'percentage' => 685
                ],
                'ticketsRecorded' => [
                    'mount' => count($this->tickets),
                    'percentage' => $this->tickets
                ],
                'pasiveData' => [
                    'mount' => '$'.$this->pasiveData . ' MXN',
                    'percentage' => 685
                ],

                'graphicBar' => [
                    'keys' => array_keys($this->finalResult),
                    'values' => array_values($this->finalResult)
                ],

                'ChartPoligono' => [
                    'keys' => array_keys($this->finalResultWeek),
                    'values' => array_values($this->finalResultWeek)
                ],

                'doughnut' => [
                    'keys' => array_keys($this->previously),
                    'values' => array_values($this->previously)
                ],
                'expressCreation' => $this->expressCreation()
            ]
        );
    }

    public function expressCreation(){
        return Product::where('Description', 'EXPRESS CREATION')->get(
            ['name', 'id', 'folio', 'Description']
        );
    }

    public function prepareTicketsWithIssues(){

        $tickets = tickets::with('createdBy', 'ticketItems')->orderBy('created_at', 'desc')->get();

        for ($i=0; $i < count($tickets) ; $i++) { 
            $this->previously[$tickets[$i]->provider] = array();
            
            if(count($tickets[$i]->ticketItems) != 0){
                for ($o=0; $o < count($tickets[$i]->ticketItems); $o++) 
                    if($tickets[$i]->ticketItems[$o]->product_id == null)
                        array_push($this->previously[$tickets[$i]->provider], $tickets[$i]->ticketItems[$o]->bar_code);
            }else
                array_push($this->previously[$tickets[$i]->provider], 'SIN PRODUCTOS RELACIONADOS');
            
            if(count($this->previously[$tickets[$i]->provider]) == 0)
                unset($this->previously[$tickets[$i]->provider]);


            $dataObj = new \stdClass();
            $dataObj->ticketId = $tickets[$i]->id;
            $dataObj->date_time_issued = $tickets[$i]->date_time_issued;
            $dataObj->provider = $tickets[$i]->provider;

            if(isset($this->previously[$tickets[$i]->provider])){
                $dataObj->issues = $this->previously[$tickets[$i]->provider];
                array_push($this->toTicketsWithIssues, $dataObj);
            }
        }

        return $this->toTicketsWithIssues;
    }

    public function prepareTickets(){
        $tickets = DB::table('tickets')
                    ->leftJoin('ticket_items', 'tickets.id', '=', 'ticket_items.ticket_id')
                    ->whereBetween('date_time_issued', $this->dates['range'])
                    ->select('tickets.*', 'ticket_items.*')->get();
        for ($i=0; $i < count($tickets) ; $i++) { 
            array_push($this->tickets, $tickets[$i]->total);
            
        }
        $this->pasiveData = 100;

       return $this->tickets;

        // $this->tickets = tickets::whereBetween('date_time_issued', $this->dates['range'])->get();
    }

    public function prepareSalesByMonth(){
        $today = Carbon::now();

        $sales = DB::table('product_line_items')
            ->whereBetween('created_at', [
                Carbon::create($today->year, 1, 1, 0, 0, 0),
                Carbon::create($today->year, $today->month, $today->day, 0, 0, 0)
            ])->get();

        $graphicBar = [
            array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array()
        ];

        for ($i=0; $i < count($sales); $i++) {  
            $source = new Carbon($sales[$i]->created_at);       
            array_push($graphicBar[$source->month - 1], $sales[$i]->final_price);
        }
        
        for ($i=0; $i < count($graphicBar); $i++) { 
            $this->finalResult[$this->meses[$i]] = array_sum($graphicBar[$i]);
        }
        
    }




    public function prepareSalesByWeek() {
        $now = Carbon::now();
    

        ###Considera agregar 1 día más a la variable  $now->endOfWeek()->toDateString()
        $sales = DB::table('product_line_items')
                    ->whereBetween('created_at', [$now->startOfWeek()->toDateString(), $now->endOfWeek()->addDay(1)->toDateString()])
                    ->get(['created_at', 'final_price']); // Fetch only necessary fields

    
        // Initialize the array to hold summed prices for each day of the week
        $sumsByDayOfWeek = array_fill(0, 7, 0);
    
        // Sum the final prices for each day of the week
        foreach ($sales as $sale) {
            $dayOfWeek = Carbon::parse($sale->created_at)->dayOfWeekIso - 1;
            $sumsByDayOfWeek[$dayOfWeek] += $sale->final_price;
        }
        
        // Prepare the final result with day names
        // $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $this->finalResultWeek = array_combine($this->dias, $sumsByDayOfWeek);

    }

    /**
     * @return 2 dates based on the request of index method to define the results.
     */
    private function getBothDates(){
        if($this->typeView == 'custom_view'){
            $this->startDate    = $this->init['startDate'];
            $this->endDate      = $this->init['endDate'];
        }else if($this->typeView == 'standard_view'){
            $this->startDate    = Carbon::today();
            $this->endDate      = Carbon::tomorrow();
        }
        return [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'range' =>[
                $this->startDate,
                $this->endDate
            ]
        ];
    }

    public function prepareSalesForDashboard(){
        
        $this->dates = $this->getBothDates();
        $this->sales = Sales::whereBetween('created_at', $this->dates['range'])->get();
        
        for ($i=0; $i < count($this->sales); $i++) {
            $this->salesCounts = $this->salesCounts + $this->sales[$i]->total;
            array_push($this->toSearchInProducts, $this->sales[$i]->id);
        }

    }

    public function cardSales(){

        return response()->json([
            'stores' => Store::get(['id', 'name']),
            'sales' => Sales::whereBetween('created_at', [Carbon::today(),Carbon::tomorrow() ])->get()
        ]);
    }

    public function prepareProductsSoldDashboard(){
        $this->products = Product::get(['name', 'id', 'folio', 'Description', 'price_list','price_customer','profit_percentage']);
        $toDefineProductsById = null;
        for ($i=0; $i < count($this->products); $i++) { 
            $toDefineProductsById[$this->products[$i]->id] = $this->products[$i];
        }
        $this->plis = DB::table('product_line_items')
                            ->whereIn('sale_id', $this->toSearchInProducts)
                            ->get();


        
        for ($o=0; $o < count($this->plis); $o++) { 
            $this->plis[$o]->product = $toDefineProductsById[$this->plis[$o]->product_id];
        }
    }

    public function getProductsSoldDashboard(){
        
        return number_format(count($this->plis), 0, ',');
    }

    public function getSalesTotal(){
        return number_format($this->salesCounts, 2, '.', ',');
    }

}
