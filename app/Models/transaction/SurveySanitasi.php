<?php

namespace App\Models\transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveySanitasi extends Model
{
    use HasFactory;
    protected $table = "surveysanitasis";
    protected $primaryKey = "id";

    protected $fillable = [
        'id',
        'uuid',
        'name',
        'longitude',
        'latitude',
        'image',
        'nik',
        'address2rt',
        'address2rw',
        'toiletstatus_id',
        'toilettype_id',
        'toilettpa_id',
        'kelurahan_id',
        'kecamatan_id',
        'kab_user_id',
        'kec_user_id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'updated_at'
    ];
}
