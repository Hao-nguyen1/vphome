<?php

namespace App\Repositories;

use App\Models\Distribution;
use App\Repositories\Interfaces\DistributionRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class DistributionRepository extends BaseRepository implements DistributionRepositoryInterface
{
    protected $model;

    public function __construct(
        Distribution $model
    ){
        $this->model = $model;
    }


}
