<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Repositories\ApiUserRepositoryInterface;

class ApiUserController extends Controller
{
    protected $repository;

    public function __construct(ApiUserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function show()
    {
        $user = $this->repository->getAuthenticatedUser();
        return new UserResource($user);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->repository->getAuthenticatedUser();

        $this->repository->updateProfile($user, $request->validated());

        return new UserResource($user);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $this->repository->getAuthenticatedUser();

        $this->repository->updatePassword($user, $request->password);

        return response()->json(['message' => 'Password updated successfully.']);
    }
}
