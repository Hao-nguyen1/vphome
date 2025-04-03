<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Traits\QueryScopes;

class Product extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'image',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
        'product_catalogue_id',
        'price',

        'made_in',
        'code',
        'attributeCatalogue',
        'attribute',
        'variant',
        'qrcode',
        'warranty',
        'check'
    ];

    protected $casts = [
        'attribute' => 'json'
    ];

    protected $table = 'products';

    public function languages(){
        return $this->belongsToMany(Language::class, 'product_language' , 'product_id', 'language_id')
        ->withPivot(
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content',
            'url',
        )->withTimestamps();
    }
    public function product_catalogues(){
        return $this->belongsToMany(ProductCatalogue::class, 'product_catalogue_product' , 'product_id', 'product_catalogue_id');
    }

    public function product_variants(){
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    public function promotions(){
        return $this->belongsToMany(Promotion::class, 'promotion_product_variant' , 'product_id', 'promotion_id')
        ->withPivot(
            'variant_uuid',
            'model',
        )->withTimestamps();
    }


    public function orders(){
        return $this->belongsToMany(Order::class, 'order_product' , 'product_id', 'order_id')
        ->withPivot(
            'uuid',
            'name',
            'qty',
            'price',
            'priceOriginal',
            'option',
        );
    }

    public function reviews(){
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function constructions(){
        return $this->belongsToMany(Construction::class, 'construction_product' , 'construction_id', 'product_id')->withPivot(
            'construction_id',
            'product_id',
            'quantity',
            'startDate',
            'endDate',
            'color',
            'warranty',
            'status',
        );;
    }
}
