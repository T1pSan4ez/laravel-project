<?php

namespace App\Repositories;

use App\Models\Hall;

interface HallRepositoryInterface
{
    public function getCinemaWithHalls(int $cinemaId);
    public function createHall(int $cinemaId, array $data):  ?Hall;
    public function findHall(int $cinemaId, int $hallId): Hall;
    public function deleteHall(int $cinemaId, int $hallId): bool;
    public function clearSeats(Hall $hall): bool;
    public function updateHallSeats(Hall $hall, array $selectedSeats, array &$errors): bool;
    public function editHall(int $cinemaId, int $hallId, array $data): ?array;
}
