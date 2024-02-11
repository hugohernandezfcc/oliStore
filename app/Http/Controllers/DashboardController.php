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
use PhpParser\Node\Expr\Cast\String_;

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
    public $products;
    public $plis;
    public $productCounts = 0;
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
        
        return response()->json(
            [   
                'typeView' => $this->typeView,
                'salesToday' => [
                    'mound' => '$'. $this->getSalesTotal() . ' MXN',
                    'percentage' => 685
                ],
                'productsToday' => [
                    'mound' => '# '. $this->getProductsSoldDashboard(),
                    'percentage' => 685
                ],
                'ticketsRecorded' => [
                    'mound' => 4563,
                    'percentage' => 685
                ],
                'pasiveData' => [
                    'mound' => 4563,
                    'percentage' => 685
                ],
            ]
        );
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
        //estoy preparando aquí el número de productos por ventas
        for ($i=0; $i < count($this->sales); $i++) {
            $soldProducts = array();
            for ($o=0; $o < count($this->plis); $o++) { 
                if($this->sales[$i]->id == $this->plis[$o]->sale_id){
                    array_push($soldProducts, $this->plis[$o]);
                }
            }
            $this->productCounts = $this->productCounts + count($soldProducts);
        }
        return number_format($this->productCounts, 2, ',', '.');
    }

    public function getSalesTotal(){
        return number_format($this->salesCounts, 2, '.', ',');
    }

}
