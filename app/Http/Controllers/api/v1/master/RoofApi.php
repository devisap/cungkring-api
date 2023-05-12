<?php

namespace App\Http\Controllers\api\v1\master;

use App\Http\Controllers\Controller;
use App\Models\master\Roof;
use Illuminate\Http\Request;

class RoofApi extends Controller
{
    public function index(Request $req){
        try {
            if($req->id){
                $roof = Roof::where('id', $req->id)->select('id', 'name')->first();
                if(!$roof){
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                }else{
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $roof
                    ], 200);
                }
            }else{
                $roofs = Roof::where('f_active', 1)
                    ->select('id', 'name');
                if($req->limit) $roofs->limit($req->limit);
    
                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $roofs->get()
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
