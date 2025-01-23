<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessProductUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3; // Número de intentos
    public $timeout = 60; // Tiempo máximo de ejecución en segundos

    protected $product;
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product, array $data)
    {
        $this->product = $product;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Actualizar el producto
            $this->product->update($this->data);

            // Limpiar caché relacionada
            Cache::forget('products');
            Cache::forget('product_' . $this->product->id);

            // Registrar éxito
            Log::info('Product updated successfully', [
                'product_id' => $this->product->id,
                'changes' => $this->data
            ]);
        } catch (\Exception $e) {
            // Registrar error
            Log::error('Failed to update product', [
                'product_id' => $this->product->id,
                'error' => $e->getMessage()
            ]);

            // Relanzar la excepción para que el job falle
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Product update job failed', [
            'product_id' => $this->product->id,
            'error' => $exception->getMessage()
        ]);
    }
}
