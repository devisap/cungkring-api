<?php

namespace App\Http\Controllers\api\v1\master;

use App\Http\Controllers\Controller;
use App\Models\master\Kecamatan;
use Exception;
use Illuminate\Http\Request;

class KecamatanApi extends Controller
{
    public function index(Request $req){
        try {
            if($req->id){
                $kecamatan = Kecamatan::where('id', $req->id)->select('id', 'kabupatenkota_id', 'name')->first();
                if(!$kecamatan){
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                }else{
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $kecamatan
                    ], 200);
                }
            }else{
                $kecamatans = Kecamatan::where('f_active', 1)
                    ->select('id', 'kabupatenkota_id', 'name');
                if($req->limit) $kecamatans->limit($req->limit);

                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $kecamatans->get()
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
