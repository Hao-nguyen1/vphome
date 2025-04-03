<?php

namespace App\Http\Controllers\Backend\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\AgencyServiceInterface  as AgencyService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface  as ProvinceRepository;
use App\Repositories\Interfaces\AgencyRepositoryInterface as AgencyRepository;
use App\Http\Requests\Agency\StoreAgencyRequest;
use App\Http\Requests\Agency\UpdateAgencyRequest;


class AgencyController extends Controller
{
    protected $agencyService;
    protected $provinceRepository;
    protected $agencyRepository;

    public function __construct(
        AgencyService $agencyService,
        ProvinceRepository $provinceRepository,
        AgencyRepository $agencyRepository,
    ){
        $this->agencyService = $agencyService;
        $this->provinceRepository = $provinceRepository;
        $this->agencyRepository = $agencyRepository;
    }

    public function index(Request $request){
        $agencys = $this->agencyService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Agency'
        ];
        $config['seo'] = __('messages.agency');
        $template = 'backend.agency.agency.index';
        return view('backend.dashboard.layout', compact(
            'config',
            'template',
            'agencys'
        ));
    }

    public function create(){
        $provinces = $this->provinceRepository->all();
        $config = $this->configData();
        $config['seo'] = __('messages.agency');
        $config['method'] = 'create';
        $config['model'] = 'Agency';
        $template = 'backend.agency.agency.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'provinces'
        ));
    }

    public function store(StoreAgencyRequest $request){
        if($this->agencyService->create($request)){
            return redirect()->route('agency.index')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('agency.index')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id, Request $request){
        $agency = $this->agencyRepository->findById($id);
        $provinces = $this->provinceRepository->all();
        $queryUrl = $request->getQueryString();
        $config = $this->configData();
        $config['seo'] = __('messages.agency');
        $config['method'] = 'edit';
        $config['model'] = 'Agency';
        $template = 'backend.agency.agency.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'provinces',
            'agency',
            'queryUrl'
        ));
    }

    public function update($id, UpdateAgencyRequest $request){
        $queryUrl = base64_decode($request->getQueryString());
        if($this->agencyService->update($id, $request)){
            return redirect()->route('agency.index', $queryUrl)->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('agency.index')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id){
        $config['seo'] = __('messages.agency');
        $agency = $this->agencyRepository->findById($id);
        $template = 'backend.agency.agency.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'agency',
            'config',
        ));
    }

    public function destroy($id){
        if($this->agencyService->destroy($id)){
            return redirect()->route('agency.index')->with('success','Xóa bản ghi thành công');
        }
        return redirect()->route('agency.index')->with('error','Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData(){
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
                'backend/library/construct.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
            ]
        ];
    }

}
