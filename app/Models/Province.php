<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $table = 'provinces';
    protected $primaryKey = 'code'; 
    public $incrementing = false;

    public function districts()
    {
        return $this->hasMany(District::class, 'province_code', 'code');
    }

    public function provinces(){
        return $this->hasMany(Order::class, 'province_id', 'code');
    }
    
}
