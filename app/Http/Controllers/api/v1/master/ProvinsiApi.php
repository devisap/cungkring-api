<?php

namespace App\Http\Controllers\api\v1\master;

use App\Http\Controllers\Controller;
use App\Models\master\Provinsi;
use Exception;
use Illuminate\Http\Request;

class ProvinsiApi extends Controller
{
    public function index(Request $req){
        try {
            if($req->id){
                $provinsi = Provinsi::where('id', $req->id)->select('id', 'name_short', 'name')->first();
                if(!$provinsi){
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                }else{
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $provinsi
                    ], 200);
                }
            }else{
                $provinsis = Provinsi::where('f_active', 1)
                    ->select('id', 'name_short', 'name');
                if($req->limit) $provinsis->limit($req->limit);
    
                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $provinsis->get()
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
