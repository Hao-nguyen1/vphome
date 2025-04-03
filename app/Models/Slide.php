<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Slide extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'keyword',
        'description',
        'item',
        'setting',
        'short_code',
        'publish',
    ];

    protected $table = 'slides';

    protected $casts = [
        'item' => 'json',
        'setting' => 'json',
    ];

}
