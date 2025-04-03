<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserService
 * @package App\Services
 */
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(
        Product $model
    ){
        $this->model = $model;
    }

    public function search($keyword, $language_id){
        return $this->model->select([
                'products.id',
                'products.product_catalogue_id',
                'products.image',
                'products.icon',
                'products.album',
                'products.publish',
                'products.follow',
                'products.price',
                'products.code',
                'products.made_in',
                'products.attributeCatalogue',
                'products.attribute',
                'products.variant',
                'products.warranty',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('product_language as tb2', 'tb2.product_id', '=','products.id')
        ->with([
            'product_catalogues',
            'product_variants' => function ($query) use ($language_id) {
                $query->with(['attributes' => function ($query) use ($language_id) {
                    $query->with(['attribute_language' => function ($query) use ($language_id) {
                        $query->where('language_id', '=', $language_id);
                    }]);
                }]);
            },
            'reviews'
        ])
        ->where('tb2.language_id', '=', $language_id)
        ->where('products.publish', '=', 2)
        ->where('tb2.name', 'LIKE', '%'.$keyword.'%')
        ->get();
    }

    public function wishlist($ids, $language_id){
        return $this->model->select([
                'products.id',
                'products.product_catalogue_id',
                'products.image',
                'products.icon',
                'products.album',
                'products.publish',
                'products.follow',
                'products.price',
                'products.code',
                'products.made_in',
                'products.attributeCatalogue',
                'products.attribute',
                'products.variant',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('product_language as tb2', 'tb2.product_id', '=','products.id')
        ->with([
            'product_catalogues',
            'product_variants' => function ($query) use ($language_id) {
                $query->with(['attributes' => function ($query) use ($language_id) {
                    $query->with(['attribute_language' => function ($query) use ($language_id) {
                        $query->where('language_id', '=', $language_id);
                    }]);
                }]);
            },
            'reviews'
        ])
        ->where('tb2.language_id', '=', $language_id)
        ->where('products.publish', '=', 2)
        ->whereIn('products.id', $ids)
        ->get();
    }

    public function getProductById(int $id = 0, $language_id = 0, $condition = []){
        return $this->model->select([
                'products.id',
                'products.product_catalogue_id',
                'products.image',
                'products.icon',
                'products.album',
                'products.publish',
                'products.follow',
                'products.price',
                'products.code',
                'products.made_in',
                'products.attributeCatalogue',
                'products.attribute',
                'products.variant',
                'products.qrcode',
                'products.warranty',
                'products.iframe',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('product_language as tb2', 'tb2.product_id', '=','products.id')
        ->with([
            'product_catalogues',
            'product_variants' => function ($query) use ($language_id) {
                $query->with(['attributes' => function ($query) use ($language_id) {
                    $query->with(['attribute_language' => function ($query) use ($language_id) {
                        $query->where('language_id', '=', $language_id);
                    }]);
                }]);
            },
            'reviews'
        ])
        ->where('tb2.language_id', '=', $language_id)
        // ->where([$condition])
        ->find($id);
    }

    public function findProductForPromotion($condition = [], $relation = []){
        $query = $this->model->newQuery();
        $query->select([
            'products.id',
            'products.image',
            'products.warranty',
            'tb2.name',
            'tb3.uuid',
            'tb3.id as product_variant_id',
            DB::raw('CONCAT(tb2.name, " - ", COALESCE(tb4.name, " Default")) as variant_name'),
            DB::raw('COALESCE(tb3.sku, products.code) as sku'),
            DB::raw('COALESCE(tb3.price, products.price) as price'),
        ]) ;
        $query->join('product_language as tb2','products.id', '=', 'tb2.product_id');
        $query->leftJoin('product_variants as tb3','products.id', '=', 'tb3.product_id');
        $query->leftJoin('product_variant_language as tb4', 'tb3.id', '=', 'tb4.product_variant_id');

        foreach($condition as $key => $val){
            $query->where($val[0], $val[1], $val[2]);
        }
        if(count($relation)){
            $query->with($relation);
        }
        $query->orderBy('id', 'desc');
        $query->groupBy('products.id');
        return $query->paginate(20);
    }

    public function filter($param, $perpage, $orderBy){
        $query = $this->model->newQuery();

        $query->select(
            'products.id',
            'products.price',
            'products.image',
        );

        if(isset($param['select']) && count($param['select'])){
            foreach($param['select'] as $key => $val){
                if(is_null($val)) continue;
                $query->selectRaw($val);
            }
        }

        if(isset($param['join']) && count($param['join'])){
            foreach($param['join'] as $key => $val){
                if(is_null($val)) continue;
                $query->leftJoin($val[0], $val[1], $val[2], $val[3]);
            }
        }

        $query->where('products.publish', '=', 2);

        if(isset($param['where']) && count($param['where'])){
            foreach($param['where'] as $key => $val){
                $query->where($val);
            }
        }

        if(isset($param['whereRaw']) && count($param['whereRaw'])){
            $query->whereRaw($param['whereRaw'][0][0], $param['whereRaw'][0][1]);
        }

        if(isset($param['having']) && count($param['having'])){
            foreach($param['having'] as $key => $val){
                if(is_null($val)) continue;
                $query->having($val);
            }
        }

        $query->groupBy($orderBy);
        $query->with(['reviews', 'languages', 'product_catalogues']);

        return $query->paginate($perpage);
    }    
    

}
