<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            'movie' => [
                'id' => $this->movie->id,
                'title' => $this->movie->title,
                'description' => $this->movie->description,
                'duration' => $this->movie->duration,
                'age_rating' => $this->movie->age_rating,
                'poster' => $this->movie->poster ? asset('storage/' . $this->movie->poster) : null,
                'genres' => $this->movie->genres->map(function ($genre) {
                    return [
                        'id' => $genre->id,
                        'name' => $genre->name,
                    ];
                }),
            ],
            'hall' => [
                'id' => $this->hall->id,
                'name' => $this->hall->name,
                'cinema' => [
                    'id' => $this->hall->cinema->id,
                    'name' => $this->hall->cinema->name,
                    'city' => [
                        'id' => $this->hall->cinema->city->id,
                        'name' => $this->hall->cinema->city->name,
                    ],
                ],
            ],
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'slots' => $this->slots->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'row' => $slot->row,
                    'number' => $slot->number,
                    'price' => $slot->price,
                    'type' => $slot->type,
                ];
            }),
        ];
    }
}
