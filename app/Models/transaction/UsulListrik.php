<?php

namespace App\Models\transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulListrik extends Model
{
    use HasFactory;

    protected $table = 'usullistriks';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'uuid',
        'fiscalyear_proposed',
        'idcard_number',
        'photo_idcard',
        'name',
        'address1',
        'address2',
        'address2rt',
        'address2rw',
        'latitude',
        'longitude',
        'mbr_status',
        'landstatus_id',
        'landstatus_doc',
        'landtenure_id',
        'house_length',
        'house_width',
        'photo1',
        'photo2',
        'kkincome_permonth',
        'household_onehouse',
        'ump_umk',
        'assistancereceived_type',
        'assistancereceived_year',
        'occupied_years',
        'electricityaccess_id',
        'verification_id',
        'reason_rejected',
        'realized_id',
        'realized_year',
        'kelurahan_id',
        'kecamatan_id',
        'kab_user_id',
        'kec_user_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'updated_at'
    ];
}
