<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'name' => $this->name,
            'cinemas' => $this->cinemas->map(function ($cinema) {
                return [
                    'id' => $cinema->id,
                    'name' => $cinema->name,
                    'address' => $cinema->address,
                ];
            }),
        ];
    }
}
