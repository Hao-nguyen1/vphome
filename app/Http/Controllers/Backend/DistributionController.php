<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\DistributionServiceInterface  as DistributionService;
use App\Repositories\Interfaces\DistributionRepositoryInterface as DistributionRepository;

use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;

use App\Http\Requests\Distribution\StoreDistributionRequest;
use App\Http\Requests\Distribution\UpdateDistributionRequest;

use App\Models\Language;
use Illuminate\Support\Collection;

class DistributionController extends Controller
{
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
        $this->middleware(function($request, $next){
            $locale = app()->getLocale(); // vn en cn
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request){
        $this->authorize('modules', 'distribution.index');
        $distributions = $this->distributionService->paginate($request);
      
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Distribution'
        ];
        $config['seo'] = __('messages.distribution');
        $template = 'backend.distribution.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'distributions'
        ));
    }

    public function create(){
        $this->authorize('modules', 'distribution.create');

        $provinces = $this->provinceRepository->all();
        $config = $this->config();
        $config['seo'] = __('messages.distribution');
        $config['method'] = 'create';
        $template = 'backend.distribution.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'provinces',
            
        ));
    }

    public function store(StoreDistributionRequest $request){
        if($this->distributionService->create($request, $this->language)){
            return redirect()->route('distribution.index')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('distribution.index')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    

    public function edit($id){
        $this->authorize('modules', 'distribution.update');
        $distribution = $this->distributionRepository->findById($id);
        $config = $this->config();
        $config['seo'] = __('messages.distribution');
        $config['method'] = 'edit';
        $template = 'backend.distribution.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'distribution',
        ));
    }

    public function update($id, UpdateDistributionRequest $request){
        if($this->distributionService->update($id, $request, $this->language)){
            return redirect()->route('distribution.index')->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('distribution.index')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id){
        $this->authorize('modules', 'distribution.destroy');
        $config['seo'] = __('messages.distribution');
        $distribution = $this->distributionRepository->findById($id);
        $template = 'backend.distribution.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'distribution',
            'config',
        ));
    }

    public function destroy($id){
        if($this->distributionService->destroy($id)){
            return redirect()->route('distribution.index')->with('success','Xóa bản ghi thành công');
        }
        return redirect()->route('distribution.index')->with('error','Xóa bản ghi không thành công. Hãy thử lại');
    }

    
    private function config(){
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
            ]
        ];
    }

}
