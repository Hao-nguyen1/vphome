<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Services\Interfaces\DistributionServiceInterface as DistributionService;
use App\Repositories\Interfaces\DistributionRepositoryInterface as DistributionRepository;

use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;

class DistributionController extends FrontendController
{
    protected $language;
    protected $system;
    protected $distributionService;
    protected $distributionRepository;
    protected $provinceRepository;

    public function __construct(
        DistributionService $distributionService,
        DistributionRepository $distributionRepository,
        ProvinceRepository $provinceRepository,
    ){
        $this->distributionService = $distributionService;
        $this->distributionRepository = $distributionRepository;
        $this->provinceRepository = $provinceRepository;
        parent::__construct(); 
    }


    public function index(Request $request){
       
        $distributions = $this->distributionRepository->all();

        $provinces = $this->provinceRepository->all();
       
        $config = $this->config();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Hệ thống phân phối',
            'meta_description' => 'Hệ thống phân phối của '.$system['homepage_company'],
            'meta_keyword' => '',
            'meta_image' => '',
            'canonical' => write_url('he-thong-phan-phoi')
        ];
        return view('frontend.distribution.index', compact(
            'config',
            'seo',
            'system',
            'provinces',
            'distributions',
        ));
    }

    private function config(){
        return [
            'language' => $this->language,
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'backend/library/location.js',
                'frontend/core/library/cart.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ]
        ];
    }

}
