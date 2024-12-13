<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Repositories\ApiSessionRepositoryInterface;

class ApiSessionController extends Controller
{
    protected $repository;

    public function __construct(ApiSessionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($id)
    {
        $session = $this->repository->getSessionById($id);

        return new SessionResource($session);
    }
}
