<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        // remove token
        $removetoken = JWTAuth::invalidate(JWTAuth::getToken());
        
        if ($removetoken) {
            // return response JSON
            return response()->json([
                'status' => true,
                'message' => 'Logout berhasil',
            ]);
        }
    }
}
