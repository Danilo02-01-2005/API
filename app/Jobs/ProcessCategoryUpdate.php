<?php

namespace App\Jobs;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProcessCategoryUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    protected $category;
    protected $data;

    public function __construct(Category $category, array $data)
    {
        $this->category = $category;
        $this->data = $data;
    }

    public function handle(): void
    {
        try {
            $this->category->update($this->data);

            Cache::forget('categories');
            Cache::forget('category_' . $this->category->id);
            Cache::forget('category_' . $this->category->id . '_products');

            Log::info('Category updated successfully', [
                'category_id' => $this->category->id,
                'changes' => $this->data
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update category', [
                'category_id' => $this->category->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Category update job failed', [
            'category_id' => $this->category->id,
            'error' => $exception->getMessage()
        ]);
    }
}
