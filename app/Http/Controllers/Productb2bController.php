<?php

namespace App\Http\Controllers;

use App\Models\Productb2b;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Models\PriceBookEntry;
use Illuminate\Support\Facades\Storage;


class Productb2bController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        
        return Inertia::render('Productsb2b/Index', [
            'productsb2b' => Productb2b::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Productsb2b/Create');
    }

    public function storeImage(Request $request)
    {
        if ($request->hasFile('file')) {

            $filePath = Storage::putFileAs('products', $request->file('file'), $request->name);

            return response()->json(['url' => Storage::url($filePath)], 200);
        }
        return response()->json($request);
    }


    public function storePriceBookEntry(Request $request)
    {
        $toReturn = array();
        for( $index = 0; $index < count($request->get('pricebookentries')); $index++){
            $entry = PriceBookEntry::updateOrCreate(
                [
                    'productb2b_id' => $request->get('parentRecordId'),
                    'pricebook_id' => $request->get('pricebookentries')[$index]['pricebook'],
                    'price'        => floatval($request->get('pricebookentries')[$index]['price']),
                ],
                [
                    'created_by_id' => Auth::id(),
                    'edited_by_id'  => Auth::id(),
                    'cost'          => $request->get('pricebookentries')[$index]['cost'],
                    'profit'        => (floatval($request->get('pricebookentries')[$index]['cost']) * floatval($request->get('pricebookProfit')))/100,       
                    'is_active'     => $request->get('pricebookentries')[$index]['isActive'],
                    'description'   => 'Ganancia de ' . $request->get('pricebookProfit') . '%',
                    'name'          => 'Lista de precios seleccionada es: ' . $request->get('pricebookentries')[$index]['pricebook']
                ]
            );
            array_push($toReturn, $entry->id);
        }

        return response()->json([
            'pricebookentries' => $toReturn,
            'message' => 'Se han guardado las entradas de la lista de precios'
        ])->setStatusCode(200);
    }

    public function changeStatus(Request $request)
    {
        $productb2b = Productb2b::find($request->get('id'));
        $productb2b->is_public = $request->get('status');
        $productb2b->save();
        return response()->json([
            'message' => 'Se ha cambiado el estatus del producto'
        ])->setStatusCode(200);
    }

    public function migrationProducts()
    {
        set_time_limit(100);
        $ids = array();
        $productsb2b = Productb2b::get();
        foreach($productsb2b as $product){
            array_push($ids, $product->folio);
        }

        $products = Product::select('name', 'folio', 'Description', 'unit_measure')
                    ->where('Description', 'NOT LIKE', '%EXPRES%')
                    ->where('Description', 'NOT LIKE', '%PROMOCI%')
                    ->whereNotIn('folio', $ids)
                    ->get();

        foreach($products as $product){
            $productb2b = Productb2b::create([
                'name' => $product->name,
                'folio' => $product->folio,
                'description' => $product->Description,
                'unit_measure' => $product->unit_measure,
                'created_by_id' => Auth::id(),
                'edited_by_id' => Auth::id(),
                'sold_out' => false,
                'is_public' => true,
                'is_private' => false,
                'public_description' => $product->Description,
                'public_name' => $product->name,
                'image' => "https://olistore-bucket.s3.us-east-2.amazonaws.com/products/CleanShot+2025-04-25+at+18.16.59%402x.png"
            ]);
        }


        
        return response()->json($products)->setStatusCode(200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'created_by_id' => Auth::id(),
            'edited_by_id' => Auth::id(),
            'sold_out' => false,
            'is_public' => true,
            'is_private' => false,
            'public_description' => $request->get('description'),
            'public_name' => $request->get('name'),
            'image' => $request->get('image')

        ]);

        Productb2b::create($request->all());
        return redirect()->route('productsb2b.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Productb2b = Productb2b::with('createdBy')->with('editedBy')->find($id);
        $Productb2c = Product::where('folio', $Productb2b->folio)->first();

        return Inertia::render('Productsb2b/Show', [
            'customRecord'      => $Productb2b,
            'pricebookentries'  => $Productb2b->pricebookEntries,
            'referenceb2c'      => $Productb2c  
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $productsb2b)
    {   
        $productb2b = Productb2b::with('createdBy')->with('editedBy')->find($productsb2b);
        return Inertia::render('Productsb2b/Edit', [
            'customRecord' => $productb2b,
            'pricebookentries' => $productb2b->pricebookEntries
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $Productb2b = Productb2b::find($id);
        $request->merge([
            'edited_by_id' => Auth::id(),
            'public_description' => $request->get('description'),
            'public_name' => $request->get('name'),
            'image' => $request->get('image')
        ]);
        $Productb2b->update($request->all());
        return redirect()->route('productsb2b.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $Productb2b = Productb2b::find($id);
        $Productb2b->pricebookEntries()->delete();
        $Productb2b->delete();
        return response()->json([
            'message' => 'Productb2b deleted successfully'
        ])->setStatusCode(200);
    }
}
