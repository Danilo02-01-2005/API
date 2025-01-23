<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ubication' => $this->ubication,
            'description' => $this->description,
            'products' => ProductResource::collection($this->products), // Siempre incluimos los productos
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
