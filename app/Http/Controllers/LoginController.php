<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Auth::attempt($request->only(['email', 'password']))) {

            $token = $request->user()->createToken('ApiToken')->plainTextToken;
            return response()->json([
                'msg' => 'Login berhasil',
                'token' => $token,
                'level' => Auth::user()->level
            ], Response::HTTP_OK);
        }

        return response()->json([
            'msg' => 'Login gagal'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function logout(Request $request)
    {
        $user = auth('sanctum')->user();
        foreach ($user->tokens as $token) {
            $token->delete();
        }

        Auth::guard('web')->logout();

        return response()->json([
            'msg' => 'Logout berhasil'
        ], Response::HTTP_OK);
    }

    public function getToken(Request $request)
    {
        $token = explode('|', $request->token);
        $token = PersonalAccessToken::where('token', hash('sha256', $token[1]))->first();
        $user = $token->tokenable;
        return response()->json($user, Response::HTTP_OK);
    }
}
