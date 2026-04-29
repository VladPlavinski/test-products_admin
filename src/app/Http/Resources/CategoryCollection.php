<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($category) {
                return [
                    'id'                => $category->id,
                    'name'              => $category->name,
                    'description'       => $category->description,
                    'products_count'    => $category->products_count ?? $category->products()->count(),
                    'created_at'        => $category->created_at,
                ];
            })
        ];
    }
}
