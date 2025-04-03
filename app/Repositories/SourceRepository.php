<?php

namespace App\Repositories;

use App\Models\Source;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class SourceRepository extends BaseRepository implements SourceRepositoryInterface
{
    protected $model;

    public function __construct(
        Source $model
    ){
        $this->model = $model;
    }


}
