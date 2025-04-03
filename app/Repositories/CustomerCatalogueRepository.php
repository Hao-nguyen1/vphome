<?php

namespace App\Repositories;

use App\Models\CustomerCatalogue;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class CustomerService
 * @package App\Services
 */
class CustomerCatalogueRepository extends BaseRepository implements CustomerCatalogueRepositoryInterface
{
    protected $model;

    public function __construct(
        CustomerCatalogue $model
    ){
        $this->model = $model;
    }
    
}
