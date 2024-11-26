<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'poster' => $this->poster ? asset('storage/' . $this->poster) : null,
            'sessions' => $this->sessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'start_time' => $session->start_time,
                    'movie_id' => $session->movie_id,
                    'hall_id' => $session->hall_id,
                ];
            }),
            'genres' => $this->genres->map(function ($genre) {
                return [
                    'id' => $genre->id,
                    'name' => $genre->name,
                ];
            }),
        ];
    }
}
