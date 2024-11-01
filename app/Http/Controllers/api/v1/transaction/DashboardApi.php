<?php

namespace App\Http\Controllers\api\v1\transaction;

use App\Http\Controllers\Controller;
use App\Models\transaction\SurveyAirBersih;
use App\Models\transaction\SurveySanitasi;
use App\Models\transaction\UsulAladin;
use App\Models\transaction\UsulListrik;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardApi extends Controller
{
    public function getUsulAladin(Request $req){
        try {
            $res['total'] = UsulAladin::where('user_id', $req->userId)->count();
            $res['process'] = UsulAladin::where([
                ['user_id', '=', $req->userId],
                ['verification_id', '=', 1]
            ])->count();
            $res['accept'] = UsulAladin::where([
                ['user_id', '=', $req->userId],
                ['verification_id', '=', 2]
            ])->count();
            $res['reject'] = UsulAladin::where([
                ['user_id', '=', $req->userId],
                ['verification_id', '=', 3]
            ])->count();

            return response([
                'status_code'    => 200,
                'status_message' => 'Data found',
                'data'           => $res
            ], 200);
        } catch (Exception $err) {
            return response([
                'status_code'    => 200,
                'status_message' => $err->getMessage()
            ], 200);
        }
    }
    public function getUsulListrik(Request $req){
        try {
            $res['total'] = UsulListrik::where('user_id', $req->userId)->count();
            $res['process'] = UsulListrik::where([
                ['user_id', '=', $req->userId],
                ['verification_id', '=', 1]
            ])->count();
            $res['accept'] = UsulListrik::where([
                ['user_id', '=', $req->userId],
                ['verification_id', '=', 2]
            ])->count();
            $res['reject'] = UsulListrik::where([
                ['user_id', '=', $req->userId],
                ['verification_id', '=', 3]
            ])->count();

            return response([
                'status_code'    => 200,
                'status_message' => 'Data found',
                'data'           => $res
            ], 200);
        } catch (Exception $err) {
            return response([
                'status_code'    => 200,
                'status_message' => $err->getMessage()
            ], 200);
        }
    }

    public function getSurveyAirBersih(Request $req){
        try {
            $res = SurveyAirBersih::where('user_id', $req->userId);
            $res->select(
                    DB::raw('count(*) as total'), 
                    DB::raw('COALESCE(CAST(sum(peopleusehippam) as SIGNED), 0) as peopleusehippam'), 
                    DB::raw('COALESCE(CAST(sum(peopleusewaterwell) as SIGNED), 0) as peopleusewaterwell'), 
                    DB::raw('COALESCE(CAST(sum(peopleuncovered) as SIGNED), 0) as peopleuncovered'), 
                    DB::raw('COALESCE(CAST(sum(watertower) as SIGNED), 0) as watertower'));
            return response([
                'status_code'    => 200,
                'status_message' => 'Data found',
                'data'           => $res->first()
            ], 200);
        } catch (Exception $err) {
            return response([
                'status_code'    => 200,
                'status_message' => $err->getMessage()
            ], 200);
        }
    }

    public function getSurveySanitasi(Request $req){
        try {
            $res = SurveySanitasi::where('user_id', $req->userId);
            $res->select(DB::raw('count(*) as total'));
            return response([
                'status_code'    => 200,
                'status_message' => 'Data found',
                'data'           => $res->first()
            ], 200);
        } catch (Exception $err) {
            return response([
                'status_code'    => 200,
                'status_message' => $err->getMessage()
            ], 200);
        }
    }
}
