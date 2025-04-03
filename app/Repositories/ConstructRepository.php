<?php

namespace App\Repositories;

use App\Models\Construction;
use App\Repositories\Interfaces\ConstructRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserService
 * @package App\Services
 */
class ConstructRepository extends BaseRepository implements ConstructRepositoryInterface
{
    protected $model;

    public function __construct(
        Construction $model
    ){
        $this->model = $model;
    }

    public function constructPagination(
        array $column = ['*'], 
        array $condition = [], 
        int $perPage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'],
        array $join = [],
        array $relations = [],
    ){
        $query = $this->model->select($column)->where(function($query) use ($condition){
            if(isset($condition['keyword']) && !empty($condition['keyword'])){
                $query->where('name', 'LIKE', '%'.$condition['keyword'].'%')
                ->orWhere('code', 'LIKE', '%'.$condition['keyword'].'%')
                ->orWhere('invester', 'LIKE', '%'.$condition['keyword'].'%')
                ->orWhere('workshop', 'LIKE', '%'.$condition['keyword'].'%')
                ->orWhere('address', 'LIKE', '%'.$condition['keyword'].'%');
            }
            if(isset($condition['publish']) && $condition['publish'] != 0){
                $query->where('publish', '=', $condition['publish']);
            }
            return $query;
        });
        if(!empty($join)){
            $query->join(...$join);
        }
        return $query->paginate($perPage)
        ->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }

    public function findConstructByCustomer($customerId, $conditions = []){
        $query = $this->model
        ->where('customer_id', $customerId)
        ->with(['agencys']);
        if(isset($conditions['keyword']) && !empty($conditions['keyword'])) {
            $keyword = $conditions['keyword'];
            $query->where(function ($innerQuery) use ($keyword) {
                $innerQuery->where('code', 'LIKE', '%' . $keyword . '%')
                ->orWhere('name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('address', 'LIKE', '%' . $keyword . '%')
                ->orWhere('workshop', 'LIKE', '%' . $keyword . '%')
                ->orWhere('invester', 'LIKE', '%' . $keyword . '%')
                ->orWhere('point', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('agencys', function ($subQuery) use ($keyword){
                    $subQuery->where('code', 'LIKE', '%' . $keyword . '%');
                });
            });
        }
        if(isset($conditions['confirm']) && !empty($conditions['confirm'])){
            $query->where('confirm', $conditions['confirm']);
        }
        return $query->paginate(20);
    }

    public function findConstructByAgency($agencyId, $conditions = []){
        $query = $this->model
        ->where('agency_id', $agencyId)
        ->with(['customers']);
        if(isset($conditions['keyword']) && !empty($conditions['keyword'])) {
            $keyword = $conditions['keyword'];
            $query->where(function ($innerQuery) use ($keyword) {
                $innerQuery->where('code', 'LIKE', '%' . $keyword . '%')
                ->orWhere('name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('address', 'LIKE', '%' . $keyword . '%')
                ->orWhere('workshop', 'LIKE', '%' . $keyword . '%')
                ->orWhere('invester', 'LIKE', '%' . $keyword . '%')
                ->orWhere('point', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('customers', function ($subQuery) use ($keyword){
                    $subQuery->where('name', 'LIKE', '%' . $keyword . '%');
                });
            });
        }
        if(isset($conditions['confirm']) && !empty($conditions['confirm'])){
            $query->where('confirm', $conditions['confirm']);
        }
        return $query->paginate(20);
    }

    public function warranty($customerId, $conditions = []){
    $query = $this->model
        ->select(
            'constructions.id',
            'constructions.code',
            'tb2.color',
            'tb2.product_id',
            'tb2.warranty',
            'tb2.quantity',
            'tb2.status',
            'tb2.startDate',
            'tb2.endDate',
            'tb4.name',
        )
        ->join('construction_product as tb2', 'constructions.id', '=', 'tb2.construction_id')
        ->join('product_language as tb4', 'tb4.product_id', '=', 'tb2.product_id')
        ->where('constructions.customer_id', $customerId)
        ->where('tb4.language_id', 1);
        if(isset($conditions['keyword']) && !empty($conditions['keyword'])) {
            $keyword = $conditions['keyword'];
            $query->where(function ($innerQuery) use ($keyword){
                $innerQuery->where('code', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tb4.name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tb2.color', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tb2.quantity', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tb2.warranty', '=', $keyword.' tháng');
            });
        }
        if(isset($conditions['confirm']) && !empty($conditions['confirm'])){
            $query->where('tb2.status', $conditions['confirm']);
        }
        return $query->get();
    }

    public function warrantyAgency($agencyId, $conditions = []){
        $query = $this->model
            ->select(
                'constructions.id',
                'constructions.code',
                'tb2.color',
                'tb2.product_id',
                'tb2.warranty',
                'tb2.quantity',
                'tb2.status',
                'tb2.startDate',
                'tb2.endDate',
                'tb4.name',
            )
            ->join('construction_product as tb2', 'constructions.id', '=', 'tb2.construction_id')
            ->join('product_language as tb4', 'tb4.product_id', '=', 'tb2.product_id')
            ->where('constructions.agency_id', $agencyId)
            ->where('tb4.language_id', 1);
            if(isset($conditions['keyword']) && !empty($conditions['keyword'])) {
                $keyword = $conditions['keyword'];
                $query->where(function ($innerQuery) use ($keyword){
                    $innerQuery->where('code', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tb4.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tb2.color', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tb2.quantity', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tb2.warranty', '=', $keyword.' tháng');
                });
            }
            if(isset($conditions['confirm']) && !empty($conditions['confirm'])){
                $query->where('tb2.status', $conditions['confirm']);
            }
            return $query->get();
        }

    public function updateWarrantyStatus($payload, $status){
        DB::table('construct_product')
        ->where('construct_id', $constructId)
        ->where('product_id', $productId)
        ->update(['warranty' => 'new_warranty']);
    }


    public function getWarrantyRequest(
        array $column = ['*'],
        array $condition = [],
        int $perPage = 1,
        array $extend = [],
        array $orderBy = ['constructions.id', 'DESC'],
        array $join = [],
        array $relations = []
    ){
        $query = $this->model->select($column)
            ->where('tb4.language_id', '=', 1)
            ->join('customers', 'customers.id', '=', 'constructions.customer_id')
            ->with(['agencys']);
        if (isset($condition['keyword']) && !empty($condition['keyword'])) {
            $query->where(function ($query) use ($condition){
                $keyword = $condition['keyword'];
                $query->where('constructions.code', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('constructions.invester', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tb4.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('customers.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('customers.phone', 'LIKE', '%' . $keyword . '%')
                    ->orWhereHas('agencys', function ($subQuery) use ($keyword){
                        $subQuery->where('code', 'LIKE', '%' . $keyword . '%');
                    });
            });
        }
        if(isset($condition['status']) && !empty($condition['status'])){
            $query->where('tb2.status', $condition['status']);
        }
        if (!empty($join)) {
            foreach ($join as $joinItem) {
                $query->join(...$joinItem);
            }
        }
        return $query->orderBy($orderBy[0], $orderBy[1])
        ->paginate($perPage)
        ->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }

    public function findCustomerByConstruct($agency_id = 0){
        $query = $this->model
        ->join('customers', 'customers.id', '=', 'constructions.customer_id')
        ->where('constructions.agency_id', $agency_id);
        return $query->paginate(20);
    }
    
}