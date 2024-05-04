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

class ProcessSalesIntoStock implements ShouldQueue, ShouldBeUnique
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
        

        $productos = ProductLineItem::with("productId")
            ->where("sale_id", $this->saleLocalId)
            ->get();

        
            $productSubtracted = [];
            for ($i = 0; $i < count($productos); $i++) {
            $pIdLocal =
                $productos[$i]->productId->id . "@" . $productos[$i]->productId->folio;
            if (isset($productSubtracted[$pIdLocal])) {
                $productSubtracted[$pIdLocal]++;
            } else {
                $productSubtracted[$pIdLocal] = 1;
            }
            }

            $listFinal = [];

            foreach ($productSubtracted as $key => $value) {
                try {
                    $productKey = explode("@", $key);

                    $product = Stock::where([
                        ["product_id", $productKey[0]],
                        ["folio", $productKey[1]]
                    ])->first();
                    $product->quantity = $product->quantity - $value;

                    $product->save();
                } catch (\Throwable $th) {
                    $producto = Product::where("id", $productKey[0])->first();

                    $product = new Stock();
                    $product->name = $producto->name;
                    $product->folio = $productKey[1];
                    $product->product_id = $productKey[0];
                    $product->description = $producto->Description;
                    $product->quantity = 0 - $value;
                    $product->created_by_id = $producto->created_by_id;
                    $product->edited_by_id = $producto->edited_by_id;
                    $product->store_id = 3;
                    $product->provider_id = 2;
                    $product->profit = 0;
                    $product->investment = 0;
                    $product->save();
                }

                array_push($listFinal, $product);
            }

    }

}
