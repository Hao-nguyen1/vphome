<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\PermissionServiceInterface  as PermissionService;
use App\Repositories\Interfaces\PermissionRepositoryInterface  as PermissionRepository;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;

class PermissionController extends Controller
{
    protected $permissionService;
    protected $permissionRepository;

    public function __construct(
        PermissionService $permissionService,
        PermissionRepository $permissionRepository
    ){
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request){
        $this->authorize('modules', 'permission.index');
        $permissions  = $this->permissionService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Permission',
        ];
        $config['seo'] = __('messages.permission');
        $template = 'backend.permission.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'permissions'
        ));
    }

    public function create(){
        $this->authorize('modules', 'permission.create');
        $config = $this->configData();
        $config['seo'] = __('messages.permission');
        $config['method'] = 'create';
        $config['model'] = 'Permission';
        $template = 'backend.permission.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }

    public function store(StorePermissionRequest $request){
        if($this->permissionService->create($request)){
            return redirect()->route('permission.create')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('permission.index')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id){
        $this->authorize('modules', 'permission.update');
        $permission = $this->permissionRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = __('messages.permission');
        $config['method'] = 'edit';
        $config['model'] = 'Permission';
        $template = 'backend.permission.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'permission',
        ));
    }

    public function update($id, UpdatePermissionRequest $request){
        if($this->permissionService->update($id, $request)){
            return redirect()->route('permission.index')->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('permission.index')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id){
        $this->authorize('modules', 'permission.destroy');
        $config['seo'] = __('messages.permission');
        $permission = $this->permissionRepository->findById($id);
        $template = 'backend.permission.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'permission',
            'config',
        ));
    }

    public function destroy($id){
        if($this->permissionService->destroy($id)){
            return redirect()->route('permission.index')->with('success','Xóa bản ghi thành công');
        }
        return redirect()->route('permission.index')->with('error','Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData(){
        return [
           
          
        ];
    }

   

}
