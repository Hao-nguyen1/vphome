<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\DistributionRepositoryInterface  as DistributionRepository;

class DistributionController extends Controller
{

    protected $distributionRepository;
    protected $provinceRepository;

    public function __construct(
        DistributionRepository $distributionRepository,
    ){
        $this->distributionRepository = $distributionRepository;
    }

    public function getMap(Request $request){

        $id = $request->input('id');
        $distribution = $this->distributionRepository->findById($id);
        
        return response()->json($distribution->map); 
    }

   
   
}
