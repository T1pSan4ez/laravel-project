<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendedSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'session_id' => $this->id,
            'city_name' => $this->hall->cinema->city->name,
            'cinema_name' => $this->hall->cinema->name,
            'movie_title' => $this->movie->title,
            'start_time' => $this->start_time,
        ];
    }
}
