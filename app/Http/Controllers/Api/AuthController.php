<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = Validator::make($request->all(), [
            'username' => 'required|exists:users,username',
            'password' => 'required',
        ]);
        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ]);
        }
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ])) {
            $user = Auth::user();
            return response()->json([
                'data' => $user,
                'token' => $user->createToken('token')->plainTextToken
            ]);
        }
        return response()->json([
            'errors' => 'نام کاربری یا رمز عبور اشتباه است'
        ]);


    }

    public function register(Request $request)
    {
        $data = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required',
        ]);
        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ]);
        }
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'data' => $user,
            'token' => $user->createToken('token')->plainTextToken
        ]);
    }
}
