<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthApi extends Controller
{
    public function login(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'email'     => 'required',
                'password'  => 'required' 
            ], [
                'required'  => 'Paramater :attribute required',
                'email'     => 'Paramter :attribute not valid'
            ]);
    
            if($validator->fails()){
                return response([
                    'status_code'    => 400,
                    'status_message' => $validator->errors()->first()
                ], 400);
            }
    
            $user = User::where([
                ['email', '=', $req->email],
                ['password', '=', hash('sha256', md5($req->password))]
            ])
            ->select('id', 'email', 'name')
            ->first();
    
            if($user == null){
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Your email or password is incorrect'
                ]);
            }
    
            $jwt = JWT::encode($user->toArray(), env('JWT_SECRET_KEY'), env("JWT_ALGO"));
            return response([
                'status_code'    => 200,
                'status_message' => 'Login successfuly',
                'data'           => (object)['jwt' => $jwt]
            ], 200);
        } catch (Exception $err) {
            return response([
                'status_code'    => 500,
                'status_message' => $err
            ], 200);
        }
    }
}