<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekLoginController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            return response()->json([
                'msg' => 'login',
                'data' => Auth::user()
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'msg' => 'logout'
            ], Response::HTTP_OK);
        }
    }
}
