<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\PurchaseResource;
use App\Http\Resources\UserResource;
use App\Interfaces\ApiUserRepositoryInterface;


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

    public function getPurchases()
    {
        $user = $this->repository->getAuthenticatedUser();
        $purchases = $this->repository->getUserPurchases($user);

        return PurchaseResource::collection($purchases);
    }

    public function updateAvatar(UpdateAvatarRequest $request)
    {
        $user = $this->repository->getAuthenticatedUser();
        $file = $request->file('avatar');

        if ($this->repository->updateAvatar($user, $file)) {
            return response()->json(['message' => 'Avatar updated successfully.']);
        }

        return response()->json(['message' => 'Failed to update avatar.'], 500);
    }
}
