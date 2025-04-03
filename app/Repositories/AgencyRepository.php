<?php

namespace App\Repositories;

use App\Models\Agency;
use App\Repositories\Interfaces\AgencyRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class AgencyRepository extends BaseRepository implements AgencyRepositoryInterface
{
    protected $model;

    public function __construct(
        Agency $model
    ){
        $this->model = $model;
    }

    public function agencyPagination(
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
        });
        if(!empty($join)){
            $query->join(...$join);
        }
        return $query->paginate($perPage)
        ->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }

}