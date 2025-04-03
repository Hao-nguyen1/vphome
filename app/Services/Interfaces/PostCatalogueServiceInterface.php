<?php

namespace App\Services\Interfaces;

/**
 * Interface UserCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface PostCatalogueServiceInterface
{
    public function paginate($request, $languageId);
}
