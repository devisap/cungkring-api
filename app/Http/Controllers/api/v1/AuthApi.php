<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Exception;
use Firebase\JWT\JWT;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
    
            $user = User::where('email', $req->email)
            ->select('id', 'email', 'name', 'password', 'level_id', 'jobposition_id')
            ->first();
            
            if(!$user){
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Data not found'
                ]);
            }
    
            if(!Hash::check($req->password, $user->password)){
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Your email or password is incorrect'
                ]);
            }

            return response([
                'status_code'    => 200,
                'status_message' => 'Login successfuly',
                'data'           => (object)['userId' => $user->id]
            ], 200);
        } catch (Exception $err) {
            return response([
                'status_code'    => 500,
                'status_message' => $err->getMessage()
            ], 200);
        }
    }
}