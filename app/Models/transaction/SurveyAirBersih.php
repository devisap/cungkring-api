<?php

namespace App\Models\transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAirBersih extends Model
{
    use HasFactory;
    protected $table = "surveyairbersihs";
    protected $primaryKey = "id";

    protected $fillable = [
        'id',
        'uuid',
        'name',
        'peopleusehippam',
        'peopleusewaterwell',
        'peopleuncovered',
        'watertower',
        'waterresource_id',
        'watercondition_id',
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
