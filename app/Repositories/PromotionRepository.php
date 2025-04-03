<?php

namespace App\Repositories;

use App\Models\Promotion;
use App\Repositories\Interfaces\PromotionRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class PromotionRepository extends BaseRepository implements PromotionRepositoryInterface
{
    protected $model;

    public function __construct(
        Promotion $model
    ){
        $this->model = $model;
    }


    public function findByProduct(array $productId = []){
        return $this->model->select(
            'promotions.id as promotion_id',
            'promotions.discountValue',
            'promotions.discountType',
            'promotions.maxDiscountValue',
            'products.id as product_id',
            'products.price as product_price'
        )
        ->selectRaw(
            "
                MAX(
                    IF(promotions.maxDiscountValue != 0,
                        LEAST(
                            CASE 
                                WHEN discountType = 'cash' THEN discountValue
                                WHEN discountType = 'percent' THEN products.price * discountValue / 100
                            ELSE 0
                            END,
                            promotions.maxDiscountValue 
                        ),
                        CASE 
                                WHEN discountType = 'cash' THEN discountValue
                                WHEN discountType = 'percent' THEN products.price * discountValue / 100
                        ELSE 0
                        END
                    )
                ) as discount
            "
        )
        ->join('promotion_product_variant as ppv', 'ppv.promotion_id', '=', 'promotions.id')
        ->join('products', 'products.id', '=', 'ppv.product_id')
        ->where('products.publish', 2)
        ->where('promotions.publish', 2)
        ->whereIn('ppv.product_id', $productId)
        ->whereDate('promotions.endDate', '>', now())
        ->whereDate('promotions.startDate', '<', now())
        ->groupBy('ppv.product_id')
        ->get();
    }

    public function findPromotionByVariantUuid($uuid = ''){
        return $this->model->select(
            'promotions.id as promotion_id',
            'promotions.discountValue',
            'promotions.discountType',
            'promotions.maxDiscountValue',
            
        )
        ->selectRaw(
            "
                MAX(
                    IF(promotions.maxDiscountValue != 0,
                        LEAST(
                            CASE 
                                WHEN discountType = 'cash' THEN discountValue
                                WHEN discountType = 'percent' THEN pv.price * discountValue / 100
                            ELSE 0
                            END,
                            promotions.maxDiscountValue 
                        ),
                        CASE 
                                WHEN discountType = 'cash' THEN discountValue
                                WHEN discountType = 'percent' THEN pv.price * discountValue / 100
                        ELSE 0
                        END
                    )
                ) as discount
            "
        )
        ->join('promotion_product_variant as ppv', 'ppv.promotion_id', '=', 'promotions.id')
        ->join('product_variants as pv', 'pv.uuid', '=', 'ppv.variant_uuid')
        ->where('promotions.publish', 2)
        ->where('ppv.variant_uuid', $uuid)
        ->whereDate('promotions.endDate', '>', now())
        ->whereDate('promotions.startDate', '<', now())
        ->orderByDesc('discount') 
        ->first();
    }

    public function getPromotionByCartTotal()
    {
        return $this->model
        ->where('promotions.publish', 2)
        ->where('promotions.method', 'order_amount_range')
        ->whereDate('promotions.endDate', '>=', now())
        ->whereDate('promotions.startDate', '<=', now())
        ->get();
    }
    

    


    
}
