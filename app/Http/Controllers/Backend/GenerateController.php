<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\GenerateServiceInterface  as GenerateService;
use App\Repositories\Interfaces\GenerateRepositoryInterface  as GenerateRepository;
use App\Http\Requests\Generate\StoreGenerateRequest;
use App\Http\Requests\Generate\UpdateLanguageRequest;
use App\Http\Requests\Generate\TranslateRequest;

class GenerateController extends Controller
{
    protected $generateService;
    protected $generateRepository;

    public function __construct(
        GenerateService $generateService,
        GenerateRepository $generateRepository
    ){
        $this->generateService = $generateService;
        $this->generateRepository = $generateRepository;
    }

    public function index(Request $request){
        $this->authorize('modules', 'generate.index');
        $generates = $this->generateService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Generate',
        ];
        $config['seo'] = __('messages.generate');
        $template = 'backend.generate.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'generates'
        ));
    }

    public function create(){
        $this->authorize('modules', 'generate.create');
        $config = $this->configData();
        $config['seo'] = __('messages.generate');
        $config['method'] = 'create';
        $config['model'] = 'Generate';
        $template = 'backend.generate.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }

    public function store(StoreGenerateRequest $request){
        if($this->generateService->create($request)){
            return redirect()->route('generate.index')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('generate.index')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id){
        $this->authorize('modules', 'generate.update');
        $generate = $this->generateRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = __('messages.generate');
        $config['method'] = 'edit';
        $config['model'] = 'Generate';
        $template = 'backend.generate.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'generate',
        ));
    }

    public function update($id, UpdateGenerateRequest $request){
        if($this->generateService->update($id, $request)){
            return redirect()->route('generate.index')->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('generate.index')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id){
        $this->authorize('modules', 'generate.delete');
        $config['seo'] = __('messages.generate');
        $generate = $this->generateRepository->findById($id);
        $template = 'backend.generate.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'generate',
            'config',
        ));
    }

    public function destroy($id){
        if($this->generateService->destroy($id)){
            return redirect()->route('generate.index')->with('success','Xóa bản ghi thành công');
        }
        return redirect()->route('generate.index')->with('error','Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData(){
        return [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ]
        ];
    }


}
