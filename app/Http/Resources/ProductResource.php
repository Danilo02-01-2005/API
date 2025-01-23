<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Obtener los campos solicitados de la query string
        $fields = $request->input('fields') ?
                 explode(',', $request->input('fields')) :
                 ['id', 'name', 'price', 'description', 'category_id', 'created_at'];

        // Crear array con todos los campos disponibles
        $allFields = [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'category' =>$this->category->name,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Retornar solo los campos solicitados
        return array_intersect_key($allFields, array_flip($fields));
    }
}
