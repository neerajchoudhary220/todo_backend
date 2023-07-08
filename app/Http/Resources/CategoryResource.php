<?php

namespace App\Http\Resources;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public static $wrap = 'categories';

    public function toArray(Request $request): array
    {

        $items  = Item::where('category_id', $this->id)->get();
        return [
            "id" => $this->id,
            "category" => $this->name,
            "items" => $this->when($items->count() != 0, function () use ($items) {
                return ItemResource::collection($items);
            }),

        ];
    }
}
