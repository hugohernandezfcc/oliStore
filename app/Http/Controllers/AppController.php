<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\LineAnyItem;
use App\Models\Productb2b;

class AppController extends Controller
{

    public function customerRouter(){
        return Inertia::render('CustomerRouter');
    }

    public function whatsappRender(){
        return view('app2');
    }


    public function validPhoneNumber(String $phoneNumber){
        $account = Account::with('contacts')->where('phone', $phoneNumber)->first();


        if($account){
            return response()->json([
                'status' => true,
                'account' => $account
            ]);
        }else{


            return response()->json([
                'status' => false,
                'account' => null
            ]);
        }

    }


    public function utilityLoadProducts(string $filter, string $format){
        set_time_limit(100);
        $productosWpbe = Productb2b::where('is_public', '=', true)
                                   ->where($filter, '=', true)
                                   ->with('pricebookEntries')
                                   ->orderBy('order', 'asc')
                                   ->limit(80)
                                   ->get();
        $productosWpbe = $productosWpbe->filter(function ($producto) {
            return $producto->pricebookEntries->isNotEmpty();
        });

        $productosWpbe = $productosWpbe->map(function ($producto) {
            return [
                'id' => $producto->id,
                'name' => $producto->name,
                'description' => $producto->description,
                'price_details' => $producto->pricebookEntries->map(function ($price){
                    if ($price->is_active == 0) {
                        return null;
                    }
                    return [
                        'pricebook' => $price->pricebook->name,
                        'price' => $price->price,
                        'cost' => $price->cost,
                    ];
                }),
                'price' => $producto->pricebookEntries->map(function ($price){
                    if ($price->is_active == 0) {
                        return null;
                    }
                    return $price->price;
                })->first(),
                'image' => $producto->image,
                'unit_type' => ($producto->unit_measure == 'Granel') ? 'grams' : 'unit',
                'unit_subtype' => '1',
                'quantity' => 0,
                'promo' => $producto->promo,
                'bulkSale'  => $producto->bulkSale,
                'drinks'    => $producto->drinks,
                'snacks'    => $producto->snacks,
                'groceries' => $producto->groceries,
                'cleaning'  => $producto->cleaning,
                'underFox'  => $producto->underFox,
                'package'  => $producto->package,
                'bundle' => $producto->bundle,
                'cigars' => $producto->cigars,
                'bimbo' => $producto->bimbo,
                'marinela' => $producto->marinela,
                'sabritas' => $producto->sabritas,
                'barcel' => $producto->barcel
            ];
        });
        if($format == 'json') {
            return response()->json($productosWpbe->toArray());
        }else{
            return $productosWpbe;
        }
    }



    /**
     * Display a listing of the resource.
     */
    public function index(string $whatsappNumber)
    {

        $account = Account::where('phone', $whatsappNumber)->first();
        if($whatsappNumber == '5522539923'){
            $account->manager = true;
        }

        return Inertia::render('Welcome',
            [
                'whatsappNumber' => $whatsappNumber,
                'account' => $account,
                'ProductsB2B' => $this->utilityLoadProducts('drinks', 'php')
            ]
        );
    }

    public function search(String $searchWord){
        $lineAnyItems = LineAnyItem::where('name', 'like', '%' . $searchWord . '%')->get();
        return Inertia::render('Search', [
            'lineAnyItems' => $lineAnyItems
        ]);
    }


}
