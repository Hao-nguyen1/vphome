<?php

namespace App\Services\Interfaces;

/**
 * Interface UserCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface PostServiceInterface
{
    public function paginate($request, $languageId);
}
