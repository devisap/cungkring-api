<?php

namespace App\Http\Controllers\api\v1\master;

use App\Http\Controllers\Controller;
use App\Models\master\Kelurahan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelurahanApi extends Controller
{
    public function index(Request $req){
        try {
            if($req->id){
                $kelurahan = Kelurahan::where('id', $req->id)->select('id', 'kecamatan_id', 'name')->first();
                if(!$kelurahan){
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                }else{
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $kelurahan
                    ], 200);
                }
            }else{
                $kelurahans = Kelurahan::where('f_active', 1)
                    ->select('id', 'kecamatan_id', 'name');

                if($req->search){
                    $search = strtolower($req->search);
                    $kelurahans->where(function($query) use ($search){
                        $query->where(DB::raw('LOWER(name)'), 'LIKE', '%'.$search.'%');
                    });
                }
                                        
                if($req->kecamatanId) $kelurahans->where('kecamatan_id', $req->kecamatanId);
                if($req->limit) $kelurahans->limit($req->limit);

                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $kelurahans->get()
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
