<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class CheckAuthApi
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $jwt    = JWT::decode($request->header('Authorization'), new Key(env('JWT_SECRET_KEY'), env('JWT_ALGO')));
            $user   = User::find($jwt->id);
            
            if(!$user){
                return response([
                    'status_code' => 401,
                    'status_message' => 'User not registered'
                ], 200);
            }

            return $next($request);
        } catch (Exception $err) {
            return response([
                'status_code'    => 401,
                'status_message' => "Authentication failed"
            ], 200);
        }
    }
}
