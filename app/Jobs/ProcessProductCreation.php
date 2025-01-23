<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessProductCreation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    protected $productData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $productData)
    {
        $this->productData = $productData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Crear el producto
            $product = Product::create($this->productData);

            // Registrar éxito
            Log::info('Product created successfully', [
                'product_id' => $product->id,
                'data' => $this->productData
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create product', [
                'data' => $this->productData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
