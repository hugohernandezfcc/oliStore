<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ProductLineItem;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Store;

class ProcessSalesIntoStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $saleLocalId;

    /**
     * Create a new job instance.
     */
    public function __construct($saleId)
    {
        $this->saleLocalId = $saleId;
        $this->onQueue('table::productLineItems');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        $tiendas = Store::get();
        $matchStore =  [];
        for ($i=0; $i < count($tiendas); $i++) { 
            $matchStore[$tiendas[$i]->name] = $tiendas[$i]->id;
        }

        $productos = ProductLineItem::with("productId")
            ->with("saleId")
            ->where("sale_id", $this->saleLocalId)
            ->get();

        
            $productSubtracted = [];
            for ($i = 0; $i < count($productos); $i++) {
            $pIdLocal =
                $productos[$i]->productId->id . "@" . $productos[$i]->productId->folio . '@' . $matchStore[$productos[$i]->saleId->store] . '@' .  $productos[$i]->final_price;
            if (isset($productSubtracted[$pIdLocal])) {
                $productSubtracted[$pIdLocal]++;
            } else {
                $productSubtracted[$pIdLocal] = 1;
            }
            }

            $listFinal = [];

            foreach ($productSubtracted as $key => $value) {

                $productKey = explode("@", $key);
                $producto2 = Product::where("id", $productKey[0])->first();

                try {

                    $product = Stock::where([
                        ["product_id", $producto2->id],
                        ["folio", $productKey[1]],
                        ["store_id", $productKey[2]],
                    ])->first();
                    
                    if($producto2->take_portion == true){
                            $priceByGr = floatval($producto2->price_customer) / 1000;
                            $gramos = floatval($productKey[3]) / $priceByGr;
                            $product->quantity = $product->quantity - floatval($gramos) * 0.001;    
                    }else{

                        $product->quantity = $product->quantity - $value;
                    }

                    $product->save();
                } catch (\Throwable $th) {
                    

                    $product = new Stock();
                    $product->name = $producto2->name;
                    $product->folio = $productKey[1];
                    $product->product_id = $productKey[0];
                    $product->description = $producto2->Description;
                    $product->quantity = 0 - $value;
                    $product->created_by_id = $producto2->created_by_id;
                    $product->edited_by_id = $producto2->edited_by_id;
                    $product->store_id = $productKey[2];
                    $product->profit = 0;
                    $product->investment = 0;
                    $product->save();
                }

                array_push($listFinal, $product);
            }

    }

}
