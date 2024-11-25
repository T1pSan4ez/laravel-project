<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Models\Session;

class ApiSessionController extends Controller
{
    public function show($id)
    {
        $session = Session::with(['hall.cinema.city', 'hall.slots'])->findOrFail($id);

        return new SessionResource($session);
    }
}
