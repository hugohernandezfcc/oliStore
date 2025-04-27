<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\LineAnyItem;
use App\Models\Productb2b;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $order)
    {
        $productosWpbe = Productb2b::with('pricebookEntries')->get();
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
                'quantity' => 0
            ];
        });

        return Inertia::render('Welcome',
            [
                'order' => $order,
                'ProductsB2B' => $productosWpbe
            ]
        );
    }

    public function search(String $searchWord){
        $lineAnyItems = LineAnyItem::where('name', 'like', '%' . $searchWord . '%')->get();
        return Inertia::render('Search', [
            'lineAnyItems' => $lineAnyItems
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
