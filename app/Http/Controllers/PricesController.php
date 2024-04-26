<?php
namespace App\Http\Controllers;

use App\Models\tickets;
use App\Models\ticketItems;
use App\Models\Sales;
use App\Models\Product;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Price;

class PricesController extends Controller
{

    public function storePrice(Request $request)
    {
        $price = new Price();
        $price->product_id = $request->product_id;
        $price->description = $request->description;
        $price->price_list = $request->price_list;
        $price->price_customer = $request->price_customer;
        $price->active = $request->activar;
        $price->revenue = $request->revenue;
        $price->porcentage_revenue = $request->porcentage_revenue;
        $price->bulk_sale = $request->bulk_sale;
        $price->created_by_id = Auth::id();
        $price->edited_by_id = Auth::id();
        $price->save();
        return response()->json($price);
    }

    public function activePrice(Request $request)
    {
        $prices = Price::where('product_id', $request->product_id)->get();
        foreach ($prices as $price) {
            $price->active = 0;
            $price->edited_by_id = Auth::id();
            $price->save();
        }

        $price = Price::where('id', $request->price_id)->first();
        $price->active = $request->active;
        $price->edited_by_id = Auth::id();
        $price->save();
        return response()->json($price);

    }

    public function deletePrice(Request $request)
    {
        $price = Price::where('id', $request->price_id)->first();
        $price->delete();
        return response()->json($price);
    }

}
