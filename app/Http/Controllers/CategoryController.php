<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Product;
use App\Models\LineAnyItem;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Categories/Index', [
            'categories' => Category::with('parent')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('parent')->with('createdBy')->with('updatedBy')->find($id);
        $category->line_any_items = LineAnyItem::where('origin', 'categories')->where('category_id', $category->id)
                ->with('updatedBy')
                ->with('createdBy')
                ->with('products')
                ->with('category')
                ->with('orders')
            ->get();
            
        return Inertia::render('Categories/Show', [
            'customRecord' => $category,
            'relatedListConfig' => [
                'categories_products' => [
                    'title'          => 'Productos',
                    'titleModel'     => 'Nueva relación de productos',
                    'visibleColumns' => Product::RELATED_LIST_COLUMNS,
                    'formFields'     => Product::MODAL_FORM_FIELDS,
                    'table'          => 'products',
                    'origin'         => 'categories',
                    'origin_field'   => 'category_id',
                    'target_field'   => 'product_id',
                    'currentRecordId'=> $category->id,
                    'searchIn'       => 'name',
                    'secondLine'     => 'Description',
                    'lastLine'       => 'folio'
                ],
            ]
        ]);
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
        $Category = Category::find($id);
        $Category->delete();

        return redirect()->route('categories.index');
    }
}
