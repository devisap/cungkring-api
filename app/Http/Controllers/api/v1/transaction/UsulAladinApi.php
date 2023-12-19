<?php

namespace App\Http\Controllers\api\v1\transaction;

use App\Http\Controllers\Controller;
use App\Models\transaction\UsulAladin;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsulAladinApi extends Controller
{
    public function index(Request $req)
    {
        try {
            if ($req->id) {
                $usulAladin = UsulAladin::find($req->id);
                if (!$usulAladin) {
                    return response([
                        'status_code' => 200,
                        'status_message' => 'Data not found'
                    ], 200);
                } else {
                    return response([
                        'status_code'    => 200,
                        'status_message' => 'Data found',
                        'data'           => $usulAladin
                    ], 200);
                }
            } else {
                $usulAladins = UsulAladin::where('user_id', $req->userId)->orderBy('created_at', 'desc');
                if ($req->limit) $usulAladins->limit($req->limit);

                return response([
                    'status_code'    => 200,
                    'status_message' => 'Data found',
                    'data'           => $usulAladins->get()
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
            $usulAladins = UsulAladin::where('user_id', $req->userId)->orderBy('created_at', 'desc')
                ->select('latitude', 'longitude', 'name', 'address1', 'address2');
            if ($req->limit) $usulAladins->limit($req->limit);

            return response([
                'status_code'    => 200,
                'status_message' => 'Data found',
                'data'           => $usulAladins->get()
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
                'fisCalYear'            => 'required|integer',
                'idCard'                => 'required',
                'idCardPhoto'           => 'required|mimes:jpg,bmp,png|max:500',
                'name'                  => 'required',
                'address1'              => 'required',
                'address2'              => 'required',
                'address2rt'            => 'required|integer',
                'address2rw'            => 'required|integer',
                'latitude'              => 'required',
                'longitude'             => 'required',
                'mbr'                   => 'required|integer',
                'landStatusId'          => 'required|integer',
                'landPhoto'             => 'required|mimes:jpg,bmp,png|max:500',
                'landTenureId'          => 'required|integer',
                'homeLength'            => 'required|integer',
                'homeWidth'             => 'required|integer',
                'landFrontPhoto'        => 'required|mimes:jpg,bmp,png|max:500',
                'landSidePhoto'         => 'required|mimes:jpg,bmp,png|max:500',
                'landRTLH'              => 'required|mimes:jpg,bmp,png|max:500',
                'income'                => 'required|integer',
                'houseHold'             => 'required|integer',
                'umpUmk'                => 'required|integer',
                'assistType'            => 'nullable',
                'assistYear'            => 'nullable',
                'occupiedYear'          => 'required|integer',
                'foundationId'          => 'required|integer',
                'sloopId'               => 'required|integer',
                'columnId'              => 'required|integer',
                'beamringId'            => 'required|integer',
                'rooftrussId'           => 'required|integer',
                'wallId'                => 'required|integer',
                'floorId'               => 'required|integer',
                'roofId'                => 'required|integer',
                'drinkId'               => 'required|integer',
                'sanitationId'          => 'required|integer',
                'lightingId'            => 'required|integer',
                'ventilationId'         => 'required|integer',
                'suffId'                => 'required|integer',
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
            $newFileName    = md5(Carbon::now()->format('YmdHis') . "idCard");
            $upIdCard       = $fileIdCard->storeAs('public/idcard-photo', $newFileName . '.' . $extension);
            $urlIdCard      = Storage::url($upIdCard);

            $fileLand       = $req->file('landPhoto');
            $extension      = $fileLand->extension();
            $newFileName    = md5(Carbon::now()->format('YmdHis') . "land");
            $upLand         = $fileLand->storeAs('public/land-photo', $newFileName . '.' . $extension);
            $urlLand        = Storage::url($upLand);

            $fileLandFront  = $req->file('landFrontPhoto');
            $extension      = $fileLandFront->extension();
            $newFileName    = md5(Carbon::now()->format('YmdHis') . "landfront");
            $upLandFront    = $fileLandFront->storeAs('public/land-front-photo', $newFileName . '.' . $extension);
            $urlLandFront   = Storage::url($upLandFront);

            $fileLandSide   = $req->file('landSidePhoto');
            $extension      = $fileLandSide->extension();
            $newFileName    = md5(Carbon::now()->format('YmdHis') . "landside");
            $upLandSide     = $fileLandSide->storeAs('public/land-side-photo', $newFileName . '.' . $extension);
            $urlLandSide    = Storage::url($upLandSide);

            $fileRTLH       = $req->file('landRTLH');
            $extension      = $fileRTLH->extension();
            $newFileName    = md5(Carbon::now()->format('YmdHis') . "rtlh");
            $upRTLH         = $fileRTLH->storeAs('public/rtlh-photo', $newFileName . '.' . $extension);
            $urlRTLH        = Storage::url($upRTLH);

            $user       = User::find($req->userId);
            $formData['uuid']                       = (string) Str::uuid();
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
            $formData['photo2']                     = $urlLandSide;
            $formData['photo3']                     = $urlRTLH;
            $formData['kkincome_permonth']          = $req->income;
            $formData['household_onehouse']         = $req->houseHold;
            $formData['ump_umk']                    = $req->umpUmk;
            $formData['assistancereceived_type']    = $req->assistType;
            $formData['assistancereceived_year']    = $req->assistYear;
            $formData['occupied_years']             = $req->occupiedYear;
            $formData['foundation_id']              = $req->foundationId;
            $formData['sloop_id']                   = $req->sloopId;
            $formData['column_id']                  = $req->columnId;
            $formData['beamring_id']                = $req->beamringId;
            $formData['rooftruss_id']               = $req->rooftrussId;
            $formData['wall_id']                    = $req->wallId;
            $formData['floor_id']                   = $req->floorId;
            $formData['roof_id']                    = $req->roofId;
            $formData['dringkingwater_id']          = $req->drinkId;
            $formData['sanitation_id']              = $req->sanitationId;
            $formData['lighting_id']                = $req->lightingId;
            $formData['ventilation_id']             = $req->ventilationId;
            $formData['sufficientspace_id']         = $req->suffId;
            $formData['kelurahan_id']               = $user->kelurahan_id;
            $formData['kecamatan_id']               = $user->kecamatan_id;
            $formData['user_id']                    = $req->userId;
            $formData['created_at']                 = Carbon::now();
            $formData['verification_id']            = 1;
            UsulAladin::create($formData);

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
