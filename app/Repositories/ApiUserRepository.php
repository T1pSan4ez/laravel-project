<?php

namespace App\Repositories;

use App\Interfaces\ApiUserRepositoryInterface;
use App\Models\Purchase;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;


class ApiUserRepository implements ApiUserRepositoryInterface
{
    protected FileUploadService $fileService;

    public function __construct(FileUploadService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getAuthenticatedUser(): User
    {
        return auth()->user();
    }

    public function updateProfile(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function updatePassword(User $user, string $password): bool
    {
        return $user->update([
            'password' => Hash::make($password),
        ]);
    }

    public function getUserPurchases(User $user): Collection
    {
        return Purchase::with('items.sessionSlot')
            ->where('user_id', $user->id)
            ->get();
    }

    public function updateAvatar(User $user, UploadedFile $file): bool
    {
        $this->fileService->delete($user->avatar);

        $filePath = $this->fileService->upload($file);

        return $user->update(['avatar' => $filePath]);
    }
}
