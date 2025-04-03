<?php

namespace App\Services\Interfaces;

/**
 * Interface AttributeServiceInterface
 * @package App\Services\Interfaces
 */
interface MenuServiceInterface
{
    public function paginate($request, $languageId);
}
