<?php

namespace App\Repositories\Interfaces;

/**
 * Interface BaseServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all(array $relation, string $selectRaw = '');
    public function findById(int $id);
    public function create(array $payload);
    public function update(int $id = 0, array $payload = []);
    public function delete(int $id = 0);
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
    );
    public function updateByWhereIn(string $whereInField = '', array $whereIn = [], array $payload = []);
    public function createPivot($model, array $payload = [], string $relation = '');
    public function forceDeleteByCondition(array $condition = []);
    public function createBatch(array $payload = []);
    public function updateOrInsert(array $payload = [], array $condition = []);
    public function findByCondition($condition = [] , $flag = false, $relation = [], array $orderBy = ['id', 'desc'], array $withCount = []);
    // public function findByWhereHas(array $condition = [], string $relation = '', string $alias = '');
}
