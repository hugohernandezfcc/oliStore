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

    public function whatsappRender(string $idProduct, string $whatsappNumber){
        $product = Productb2b::find($idProduct);
        return view('app2', [
            'title' => $product->name,
            'description' => $product->description,
            'image' => $product->image,
            'idProduct' => $product->id,
            'wa' => 'https://olistore.mx/app/ecommerce/'.$whatsappNumber.'/'.$product->id
        ]);
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

    public function globalSearch(Request $request){
        $search = $request->get('search');
        $products = Productb2b::where(function($query) use ($search) {
            $string_sin_ultima_letra = substr($search, 0, -1);


            $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($search) . '%'])
                  ->orWhereRaw("LOWER(description) LIKE ?", ['%' . strtolower($search) . '%'])
                  ->orWhereRaw("LOWER(name) LIKE ?", ['%' . strtolower($string_sin_ultima_letra) . '%'])
                  ->orWhereRaw("LOWER(description) LIKE ?", ['%' . strtolower($string_sin_ultima_letra) . '%'])
                  ->orWhereRaw("LOWER(name) LIKE ?", ['%' . strtolower($string_sin_ultima_letra.'s') . '%'])
                  ->orWhereRaw("LOWER(description) LIKE ?", ['%' . strtolower($string_sin_ultima_letra.'s') . '%']);
        })
        ->where('is_public', '=', true)
        ->with('pricebookEntries')
        ->orderBy('order', 'asc')
        ->limit(80)
        ->get();

        $products = $products->filter(function ($producto) {
            return $producto->pricebookEntries->isNotEmpty();
        });
        $products = $products->map(function ($producto) {
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

        return response()->json([
            'products' => $products,
            'total' => $products->count(),
            'search' => $search
        ]);
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

    public function identifyCategory($producto){
        if($producto->drinks){
            return 'drinks';
        }else if($producto->snacks){
            return 'snacks';
        }else if($producto->groceries){
            return 'groceries';
        }else if($producto->cleaning){
            return 'cleaning';
        }else if($producto->underFox){
            return 'underFox';
        }else if($producto->package){
            return 'package';
        }else if($producto->bundle){
            return 'bundle';
        }else if($producto->cigars){
            return 'cigars';
        }else if($producto->bimbo){
            return 'bimbo';
        }else if($producto->marinela){
            return 'marinela';
        }else if($producto->sabritas){
            return 'sabritas';
        }else if($producto->barcel){
            return 'barcel';
        }else{
            return 'drinks';
        }
    }

    public function prepareProduct($producto){
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
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $whatsappNumber, string $optionalProduct = null)
    {

        $account = Account::where('phone', $whatsappNumber)->first();
        if($whatsappNumber == '5522539923'){
            $account->manager = true;
        }

        if($optionalProduct != null){
            $product = Productb2b::with('pricebookEntries')->find($optionalProduct);
            $categoria = $this->identifyCategory($product);
            return Inertia::render('Welcome',
                [
                    'whatsappNumber' => $whatsappNumber,
                    'account' => $account,
                    'ProductsB2B' => $this->utilityLoadProducts( $categoria, 'php'),
                    'optional' => [
                        'product' => $this->prepareProduct($product),
                        'category' => $categoria,
                        'actions' => []
                    ]
                ]
            );

        }else{

            return Inertia::render('Welcome',
                [
                    'whatsappNumber' => $whatsappNumber,
                    'account' => $account,
                    'ProductsB2B' => $this->utilityLoadProducts('drinks', 'php'),
                    'optional' => null
                ]
            );
        }

    }

    public function search(String $searchWord){
        $lineAnyItems = LineAnyItem::where('name', 'like', '%' . $searchWord . '%')->get();
        return Inertia::render('Search', [
            'lineAnyItems' => $lineAnyItems
        ]);
    }


}
