<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\SourceRepositoryInterface  as SourceRepository;

class SourceController extends Controller
{

    protected $sourceRepository;

    public function __construct(
        SourceRepository $sourceRepository,
    ){
        $this->sourceRepository = $sourceRepository;
    }

    public function getAllSource(){
        try {

            $sources = $this->sourceRepository->all();
            return response()->json([
                'data' => $sources,
                'error' => false 
            ]);

        }catch(\Exception $e ){
            Log::error($e->getMessage());
            return response()->json([
                'error' => true,
                'messages' =>  $e->getMessage()
            ]);
        }
    }

}
