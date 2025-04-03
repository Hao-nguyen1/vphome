<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\AgencyRepositoryInterface  as AgencyRepository;
use App\Services\Interfaces\AgencyServiceInterface  as AgencyService;
use App\Repositories\Interfaces\ConstructRepositoryInterface  as ConstructRepository;
use App\Services\Interfaces\ConstructServiceInterface  as ConstructService;
use App\Http\Requests\Agency\StoreAgencyRequest;


class ConstructController extends Controller
{
    protected $agencyRepository;
    protected $agencyService;
    protected $constructRepository;
    protected $constructService;
    
    public function __construct(
        AgencyService $agencyService,
        AgencyRepository $agencyRepository,
        ConstructRepository $constructRepository,
        ConstructService $constructService,
    ){
        $this->agencyRepository = $agencyRepository;
        $this->agencyService = $agencyService;
        $this->constructService = $constructService;
        $this->constructRepository = $constructRepository;
    }

    public function createAgency(StoreAgencyRequest $request){
        $agency = $this->agencyService->create($request); 
        if($agency !== FALSE){
            return response()->json([
                'code' => 0,
                'message' => 'Tạo đại lý thành công!',
                'data' => $agency,
            ]);
        }
        return response()->json([
            'message' => 'Có vấn đề xảy ra, hãy thử lại',
            'code' => 1
        ]);
    }
}
