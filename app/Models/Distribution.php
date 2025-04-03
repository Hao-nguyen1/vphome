<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Distribution extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'map',
        'province_id',
        'district_id',
        'publish',
    ];

    protected $table = 'distributions';

   
   
}
