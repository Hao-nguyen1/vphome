<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Interfaces\ReviewRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class ReviewService
 * @package App\Services
 */
class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    protected $model;

    public function __construct(
        Review $model
    ){
        $this->model = $model;
    }

    public function pagination(
        array $column = ['*'], 
        array $condition = [], 
        int $perPage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'],
        array $join = [],
        array $relations = [],
        array $rawQuery = [],
        // int $currentPage = 1,
        
    ){
        $query = $this->model->select($column);
        return $query  
                ->keyword($condition['keyword'] ?? null, ['fullname', 'email', 'phone', 'description'])
                ->publish($condition['publish'] ?? null)
                ->relationCount($relations ?? null)
                ->CustomWhere($condition['where'] ?? null)
                ->customWhereRaw($rawQuery['whereRaw'] ?? null)
                ->customJoin($join ?? null)
                ->customGroupBy($extend['groupBy'] ?? null)
                ->customOrderBy($orderBy ?? null)
                // ->toSql();
                ->paginate($perPage)
                ->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }


}
