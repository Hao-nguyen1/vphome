<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Agency extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, QueryScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'code',
        'password',
        'phone',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'image',
        'description',
        'ip',
        'agency_catalogue_id',
        'publish',
    ];

    protected $guard = 'agency';
    protected $table = 'agencys';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function agency_catalogues(){
        return $this->belongsTo(AgencyCatalogue::class, 'agency_catalogue_id', 'id');
    }

}