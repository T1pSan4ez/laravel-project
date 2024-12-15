<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'purchase_code' => $this->purchase_code,
            'items' => PurchaseItemResource::collection($this->items),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
