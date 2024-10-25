<?php

namespace App\Http\Controllers\api\v1\transaction;

use App\Http\Controllers\Controller;
use App\Models\transaction\SurveySanitasi;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SurveySanitasiApi extends Controller
{
    public function index(Request $req)
    {
        try {
            if ($req->id) {
                $survey = SurveySanitasi::find($req->id);
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
                $surveys = SurveySanitasi::where('user_id', $req->userId)->orderBy('created_at', 'desc');
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
            $user = User::find($req->userId);
            
            if(!$user){
                return response([
                    'status_code' => 401,
                    'status_message' => 'User not registered'
                ], 200);
            }
            
            $validator = Validator::make($req->all(), [
                'name'              => 'required',
                'address2rt'        => 'required|integer',
                'address2rw'        => 'required|integer',
                'toiletstatus_id'   => 'required|integer',
                'toilettype_id'     => 'required|integer',
                'toilettpa_id'      => 'required|integer',
                'longitude'         => 'required',
                'latitude'          => 'required',
                'photo'             => 'required|mimes:jpg,bmp,png|max:500',
            ], [
                'required'  => 'Paramater :attribute required',
                'integer'   => 'Parameter :attribute must be integer',
                'max'       => 'Parameter :attribute max 500Kb',
                'mimes'     => 'Parameter :attribute must be JPG or BMP or PNG',
            ]);

            if ($validator->fails()) {
                return response([
                    'status_code' => 400,
                    'status_message' => $validator->errors()->first()
                ], 200);
            }

            $filePhoto      = $req->file('photo');
            $extension      = $filePhoto->extension();
            $newFileName    = md5(Carbon::now()->format('YmdHis') . "photo");
            $upPhoto        = $filePhoto->storeAs('public/survey-sanitasi', $newFileName . '.' . $extension);
            $urlPhoto       = Storage::url($upPhoto);
            
            $formData['uuid']               = (string) Str::uuid();
            $formData['name']               = $req->name;
            $formData['address2rt']         = $req->address2rt;
            $formData['address2rw']         = $req->address2rw;
            $formData['toiletstatus_id']    = $req->toiletstatus_id;
            $formData['toilettype_id']      = $req->toilettype_id;
            $formData['toilettpa_id']       = $req->toilettpa_id;
            $formData['longitude']          = $req->longitude;
            $formData['latitude']           = $req->latitude;
            $formData['photo']              = $urlPhoto;
            $formData['kelurahan_id']       = $user->kelurahan_id;
            $formData['kecamatan_id']       = $user->kecamatan_id;
            $formData['user_id']            = $req->userId;
            $formData['created_at']         = Carbon::now();
            SurveySanitasi::create($formData);

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
