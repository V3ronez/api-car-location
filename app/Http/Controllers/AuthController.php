<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param Illumiante\Http\Request $request
     */
    public function login(Request $request)
    {
        $auth = $request->all(['email', 'password']);
        $token = auth('api')->attempt($auth);
        if (!$token) {
            return response()->json(['erro' => 'email or password invalid']);
        }

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['logout' => true]);
    }
    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }
    public function me()
    {
        return response()->json(auth()->user());
    }
}
