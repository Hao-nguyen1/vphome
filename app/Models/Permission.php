<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScopes;

class Permission extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'name',
        'canonical',
    ];

    protected $table = 'permissions';


    public function user_catalogues(){
        return  $this->belongsToMany(UserCatalogue::class, 'user_catalogue_permission' , 'permission_id', 'user_catalogue_id');
    }

}
