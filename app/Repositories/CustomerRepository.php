<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class CustomerService
 * @package App\Services
 */
class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    protected $model;

    public function __construct(
        Customer $model
    ){
        $this->model = $model;
    }
    
    public function customerPagination(
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
                      ->orWhere('email', 'LIKE', '%'.$condition['keyword'].'%')
                      ->orWhere('address', 'LIKE', '%'.$condition['keyword'].'%')
                      ->orWhere('phone', 'LIKE', '%'.$condition['keyword'].'%');
            }
            if(isset($condition['publish']) && $condition['publish'] != 0){
                $query->where('publish', '=', $condition['publish']);
            }
            return $query;
        })->with(['customer_catalogues', 'sources']);
        if(!empty($join)){
            $query->join(...$join);
        }

        return $query->paginate($perPage)
            ->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }

    public function getCustomer($customer_id = [], $condition = [])
    {
        $query = $this->model->select(
            'id',
            'code',
            'name',
            'phone',
            'email',
            'address'
        )->whereIn('id', $customer_id);
        if(isset($condition['keyword']) && !empty($condition['keyword'])){
            $keyword = $condition['keyword'];
            $query->where('name', 'LIKE', '%'.$keyword.'%')
                ->orWhere('code', 'LIKE', '%'.$keyword.'%')
                ->orWhere('email', 'LIKE', '%'.$keyword.'%')
                ->orWhere('address', 'LIKE', '%'.$keyword.'%')
                ->orWhere('phone', 'LIKE', '%'.$keyword.'%');
        }
        return $query->paginate(20);
    }



    public function totalCustomer(){
        return $this->model->count();
    }
}
