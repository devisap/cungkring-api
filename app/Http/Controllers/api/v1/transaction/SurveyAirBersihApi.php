<?php

namespace App\Http\Controllers\api\v1\transaction;

use App\Http\Controllers\Controller;
use App\Models\transaction\SurveyAirBersih;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SurveyAirBersihApi extends Controller
{
    public function index(Request $req)
    {
        try {
            if ($req->id) {
                $survey = SurveyAirBersih::find($req->id);
                if (!$survey) {
                    return response([
                        'status_code' => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                } else {
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $survey
                    ], 200);
                }
            } else {
                $surveys = SurveyAirBersih::where('user_id', $req->userId)->orderBy('created_at', 'desc');
                if ($req->limit) $surveys->limit($req->limit);

                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $surveys->get()
                ], 200);
            }
        } catch (Exception $err) {
            return response([
                'status_code'    => 200,
                'status_message' => $err->getMessage()
            ]);
        }
    }
    
    public function store(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'name'              => 'required',
                'peopleusehippam'   => 'required|integer',
                'peopleusewaterwell'=> 'required|integer',
                'peopleuncovered'   => 'required|integer',
                'watertower'        => 'required|integer',
                'waterresource_id'  => 'required|integer',
                'watercondition_id' => 'required|integer',
            ], [
                'required'  => 'Paramater :attribute required',
                'integer'   => 'Parameter :attribute must be integer'
            ]);

            if ($validator->fails()) {
                return response([
                    'status_code' => 400,
                    'status_message' => $validator->errors()->first()
                ], 200);
            }
            
            $user       = User::find($req->userId);
            $formData['uuid']                       = (string) Str::uuid();
            $formData['name']                       = $req->name;
            $formData['peopleusehippam']            = $req->peopleusehippam;
            $formData['peopleusewaterwell']         = $req->peopleusewaterwell;
            $formData['peopleuncovered']            = $req->peopleuncovered;
            $formData['watertower']                 = $req->watertower;
            $formData['waterresource_id']           = $req->waterresource_id;
            $formData['watercondition_id']          = $req->watercondition_id;
            $formData['kelurahan_id']               = $user->kelurahan_id;
            $formData['kecamatan_id']               = $user->kecamatan_id;
            $formData['user_id']                    = $req->userId;
            $formData['created_at']                 = Carbon::now();
            SurveyAirBersih::create($formData);

            return response([
                'status_code' => 200,
                'status_message' => 'Insert data succesfuly'
            ]);
        } catch (Exception $err) {
            return response([
                'status_code'    => 500,
                'status_message' => $err->getMessage()
            ], 200);
        }
    }
}
