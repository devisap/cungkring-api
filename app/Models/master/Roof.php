<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roof extends Model
{
    use HasFactory;

    protected $table = 'roofcategories';
    protected $primaryKey = 'id';
}
