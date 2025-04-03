<?php

namespace App\Repositories;

use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Menu;
/**
 * Class MenuService
 * @package App\Services
 */
class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    protected $model;

    public function __construct(
        Menu $model
    ){
        $this->model = $model;
    }


   
}
