<?php

namespace App\Repositories;

use App\Models\ProductVariant;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class ProductVariantRepository extends BaseRepository implements ProductVariantRepositoryInterface
{
    protected $model;

    public function __construct(
        ProductVariant $model
    ){
        $this->model = $model;
    }

    public function findVariant($code, $productId, $languageId){
       $code = trim($code);
        return $this->model->where(
            [
                ['code', '=', $code],
                ['product_id', '=', $productId]
            ]
        )
        ->with('languages', function($query) use ($languageId){
            $query->where('language_id', $languageId);
        })
        ->first();
    }

    
}
