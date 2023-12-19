<?php

namespace App\Http\Controllers\api\v1\master;

use App\Http\Controllers\Controller;
use App\Models\master\Roof;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoofApi extends Controller
{
    public function index(Request $req)
    {
        try {
            if ($req->id) {
                $roof = Roof::where('id', $req->id)->select('id', 'name')->first();
                if (!$roof) {
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                } else {
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $roof
                    ], 200);
                }
            } else {
                $roofs = Roof::where('f_active', 1)
                    ->select('id', 'name');

                if ($req->search) {
                    $search = strtolower($req->search);
                    $roofs->where(function ($query) use ($search) {
                        $query->where(DB::raw('LOWER(name)'), 'LIKE', '%' . $search . '%');
                    });
                }
                if ($req->limit) $roofs->limit($req->limit);

                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $roofs->get()
                ], 200);
            }
        } catch (Exception $err) {
            return response([
                'status_code'    => 500,
                'status_message' => $err->getMessage()
            ], 200);
        }
    }
}
