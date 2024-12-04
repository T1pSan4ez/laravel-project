<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
class ApiUserController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'string|max:255',
        ]);

        $user->name = $request->input('name');

        $user->save();

        return new UserResource($user);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Пароль успешно обновлен.']);
    }

}
