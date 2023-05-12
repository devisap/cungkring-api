<?php

namespace App\Http\Controllers\api\v1\master;

use App\Http\Controllers\Controller;
use App\Models\master\KabupatenKota;
use Exception;
use Illuminate\Http\Request;

class KabupatenKotaApi extends Controller
{
    public function index(Request $req){
        try {
            if($req->id){
                $kabkot = KabupatenKota::where('id', $req->id)->select('id', 'provinsi_id', 'name')->first();
                if(!$kabkot){
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                }else{
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $kabkot
                    ], 200);
                }
            }else{
                $kabkots = KabupatenKota::where('f_active', 1)
                    ->select('id', 'provinsi_id', 'name');
                if($req->limit) $kabkots->limit($req->limit);
    
                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $kabkots->get()
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
