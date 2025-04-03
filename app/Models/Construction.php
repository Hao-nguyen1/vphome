<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Construction extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'id',
        'name',
        'code',
        'province_id',
        'customer_id',
        'agency_id',
        'workshop',
        'confirm',
        'invester',
        'point',
        'address',
        'publish',
    ];

    protected $attributes = [
        'publish' => 2,
        // 'confirm' => 'pending',
    ];

    protected $table = 'constructions';


    public function products(){
        return $this->belongsToMany(Product::class, 'construction_product' , 'construction_id', 'product_id')->withPivot(
            'construction_id',
            'product_id',
            'quantity',
            'startDate',
            'endDate',
            'color',
            'warranty',
            'status',
        );
    }

    public function customers(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function agencys(){
        return $this->belongsTo(Agency::class, 'agency_id', 'id');
    }
}