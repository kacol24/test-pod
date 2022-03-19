<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Facades\Product;
use App\Models\Product\Product as ProductModel;

class CapacityUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $capacity_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($capacity_id)
    {
        $this->capacity_id = $capacity_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = ProductModel::join('master_products','master_products.id','=','products.master_product_id')->where('master_products.capacity_id', $this->capacity_id)->select('products.*')->get();

        foreach($products as $product) {
            Product::updateStock($product);
        }
    }
}
