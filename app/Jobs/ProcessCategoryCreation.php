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

class ProcessCategoryCreation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    protected $categoryData;

    public function __construct(array $categoryData)
    {
        $this->categoryData = $categoryData;
    }

    public function handle(): void
    {
        try {
            $category = Category::create($this->categoryData);

            Cache::forget('categories');

            Log::info('Category created successfully', [
                'category_id' => $category->id,
                'data' => $this->categoryData
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create category', [
                'data' => $this->categoryData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
