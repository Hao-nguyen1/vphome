<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class AuthController extends Controller
{
    public function __construct(){

    }
    
    public function login(AuthRequest $request){
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];


        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không chính xác',
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Authenticated Token');

        return response()->json([
            'access_token' => $tokenResult->plainTextToken,
        ], 200);
    }


}
