<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Language;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $language;

    public function __construct(){
        
    }

    
}
