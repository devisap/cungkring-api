<?php

namespace App\Http\Controllers\api\v1\master;

use App\Http\Controllers\Controller;
use App\Models\master\Wall;
use Illuminate\Http\Request;

class WallApi extends Controller
{
    public function index(Request $req){
        try {
            if($req->id){
                $wall = Wall::where('id', $req->id)->select('id', 'name')->first();
                if(!$wall){
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                }else{
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $wall
                    ], 200);
                }
            }else{
                $walls = Wall::where('f_active', 1)
                    ->select('id', 'name');
                if($req->limit) $walls->limit($req->limit);
    
                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $walls->get()
                ], 200);
            }
        } catch (Exception $err) {
            return response([
                'status_code'    => 500,
                'status_message' => $err
            ], 200);
        }
    }
}
