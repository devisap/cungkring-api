<?php

namespace App\Http\Controllers\api\v1\transaction;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\transaction\UsulListrik;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsulListrikApi extends Controller
{
    public function index(Request $req)
    {
        try {
            if ($req->id) {
                $usulListrik = UsulListrik::find($req->id);
                if (!$usulListrik) {
                    return response([
                        'status_code' => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                } else {
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $usulListrik
                    ], 200);
                }
            } else {
                $usulListriks = UsulListrik::where('user_id', $req->userId)->orderBy('created_at', 'desc');
                if ($req->limit) $usulListriks->limit($req->limit);
                
                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $usulListriks->get()
                ], 200);
            }
        } catch (Exception $err) {
            return response([
                'status_code'    => 200,
                'status_message' => $err->getMessage()
            ]);
        }
    }
    
    public function getLatLong(Request $req)
    {
        try {
            $usulListriks = UsulListrik::where('user_id', $req->userId)->orderBy('created_at', 'desc')
                ->select('latitude', 'longitude', 'name', 'address1', 'address2');
            if ($req->limit) $usulListriks->limit($req->limit);
            
            return response([
                'status_code'    => 200,
                'status_message' => 'Data found',
                'data'           => $usulListriks->get()
            ], 200);
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
                'fisCalYear'        => 'required|integer',
                'idCard'            => 'required',
                'idCardPhoto'       => 'required|mimes:jpg,bmp,png|max:500',
                'name'              => 'required',
                'address1'          => 'required',
                'address2'          => 'required',
                'address2rt'        => 'required|integer',
                'address2rw'        => 'required|integer',
                'latitude'          => 'required',
                'longitude'         => 'required',
                'mbr'               => 'required|integer',
                'landStatusId'      => 'required|integer',
                'landPhoto'         => 'required|mimes:jpg,bmp,png|max:500',
                'landTenureId'      => 'required|integer',
                'homeWidth'         => 'required|integer',
                'homeLength'        => 'required|integer',
                'landFrontPhoto'    => 'required|mimes:jpg,bmp,png|max:500',
                'certificatePhoto'  => 'required|mimes:jpg,bmp,png|max:500',
                'income'            => 'required|integer',
                'houseHold'         => 'required|integer',
                'umpUmk'            => 'required|integer',
                'assistType'        => 'nullable',
                'assistYear'        => 'nullable',
                'occupiedYear'      => 'required|integer',
                'electricityId'     => 'required|integer',
            ], [
                'required'  => 'Paramater :attribute required',
                'mimes'     => 'Parameter :attribute must be JPG or BMP or PNG',
                'max'       => 'Parameter :attribute max 500Kb',
                'integer'   => 'Parameter :attribute must be integer'
            ]);

            if ($validator->fails()) {
                return response([
                    'status_code' => 400,
                    'status_message' => $validator->errors()->first()
                ], 200);
            }

            $fileIdCard     = $req->file('idCardPhoto');
            $extension      = $fileIdCard->extension();
            $newFileName    = md5(Carbon::now()->format('YmdHis') . "idCardlis");
            $upIdCard       = $fileIdCard->storeAs('public/idcard-photo', $newFileName . '.' . $extension);
            $urlIdCard      = Storage::url($upIdCard);

            $fileLand       = $req->file('landPhoto');
            $extension      = $fileLand->extension();
            $newFileName    = md5(Carbon::now()->format('YmdHis') . "landlis");
            $upLand         = $fileLand->storeAs('public/land-photo', $newFileName . '.' . $extension);
            $urlLand        = Storage::url($upLand);

            $fileLandFront  = $req->file('landFrontPhoto');
            $extension      = $fileLandFront->extension();
            $newFileName    = md5(Carbon::now()->format('YmdHis') . "landfrontlis");
            $upLandFront    = $fileLandFront->storeAs('public/land-front-photo', $newFileName . '.' . $extension);
            $urlLandFront   = Storage::url($upLandFront);

            $fileCertificate    = $req->file('certificatePhoto');
            $extension          = $fileCertificate->extension();
            $newFileName        = md5(Carbon::now()->format('YmdHis') . "certificatelis");
            $upCertificate      = $fileCertificate->storeAs('public/certificate-photo', $newFileName . '.' . $extension);
            $urlCertificate     = Storage::url($upCertificate);

            $user       = User::find($req->userId);
            $formData['uuid']                       = (string) Str::uuid();;
            $formData['fiscalyear_proposed']        = $req->fisCalYear;
            $formData['idcard_number']              = $req->idCard;
            $formData['photo_idcard']               = $urlIdCard;
            $formData['name']                       = $req->name;
            $formData['address1']                   = $req->address1;
            $formData['address2']                   = $req->address2;
            $formData['address2rt']                 = $req->address2rt;
            $formData['address2rw']                 = $req->address2rw;
            $formData['latitude']                   = $req->latitude;
            $formData['longitude']                  = $req->longitude;
            $formData['mbr_status']                 = $req->mbr;
            $formData['landstatus_id']              = $req->landStatusId;
            $formData['landstatus_doc']             = $urlLand;
            $formData['landtenure_id']              = $req->landTenureId;
            $formData['house_width']                = $req->homeWidth;
            $formData['house_length']               = $req->homeLength;
            $formData['photo1']                     = $urlLandFront;
            $formData['photo2']                     = $urlCertificate;
            $formData['kkincome_permonth']          = $req->income;
            $formData['household_onehouse']         = $req->houseHold;
            $formData['ump_umk']                    = $req->umpUmk;
            $formData['assistancereceived_type']    = $req->assistType;
            $formData['assistancereceived_year']    = $req->assistYear;
            $formData['occupied_years']             = $req->occupiedYear;
            $formData['electricityaccess_id']       = $req->electricityId;
            $formData['kelurahan_id']               = $user->kelurahan_id;
            $formData['kecamatan_id']               = $user->kecamatan_id;
            $formData['user_id']                    = $req->userId;
            $formData['verification_id']            = 1;
            $formData['created_at']                 = Carbon::now();
            UsulListrik::create($formData);

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
