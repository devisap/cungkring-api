<?php

namespace App\Http\Controllers\api\v1\master;

use App\Http\Controllers\Controller;
use App\Models\master\Floor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FloorApi extends Controller
{
    public function index(Request $req)
    {
        try {
            if ($req->id) {
                $floor = Floor::where('id', $req->id)->select('id', 'name')->first();
                if (!$floor) {
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                } else {
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $floor
                    ], 200);
                }
            } else {
                $floors = Floor::where('f_active', 1)
                    ->select('id', 'name');

                if ($req->search) {
                    $search = strtolower($req->search);
                    $floors->where(function ($query) use ($search) {
                        $query->where(DB::raw('LOWER(name)'), 'LIKE', '%' . $search . '%');
                    });
                }
                if ($req->limit) $floors->limit($req->limit);

                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $floors->get()
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
