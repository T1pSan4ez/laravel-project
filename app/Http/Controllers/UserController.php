<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->getAllNonAdminUsersPaginated(10);
        return view('admin.users', compact('users'));
    }

    public function updateUserType(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->user_type !== 'super_admin') {
            if ($request->user_type !== 'user') {
                return redirect()->back()->with('error', 'You are not allowed to assign this user type.');
            }
        }

        $this->userRepository->updateUserType($user, $request->user_type);

        return redirect()->route('users')->with('success', 'User type updated successfully.');
    }
}
