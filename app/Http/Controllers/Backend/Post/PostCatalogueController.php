<?php

namespace App\Http\Controllers\Backend\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\PostCatalogueServiceInterface  as PostCatalogueService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface  as PostCatalogueRepository;
use App\Http\Requests\Post\StorePostCatalogueRequest;
use App\Http\Requests\Post\UpdatePostCatalogueRequest;
use App\Http\Requests\Post\DeletePostCatalogueRequest;
use App\Classes\Nestedsetbie;
use Auth;
use App\Models\Language;
use Illuminate\Support\Facades\App;
class PostCatalogueController extends Controller
{

    protected $postCatalogueService;
    protected $postCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        PostCatalogueService $postCatalogueService,
        PostCatalogueRepository $postCatalogueRepository
    ){
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });


        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
    }

    private function initialize(){
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' =>  $this->language,
        ]);
    } 
 
    public function index(Request $request){
        $this->authorize('modules', 'post.catalogue.index');
        $postCatalogues = $this->postCatalogueService->paginate($request, $this->language);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'PostCatalogue',
        ];
        $config['seo'] = __('messages.postCatalogue');
        $template = 'backend.post.catalogue.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'postCatalogues'
        ));
    }

    public function create(){
        $this->authorize('modules', 'post.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('messages.postCatalogue');
        $config['method'] = 'create';
        $dropdown  = $this->nestedset->Dropdown();
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
        ));
    }

    public function store(StorePostCatalogueRequest $request){
        if($this->postCatalogueService->create($request, $this->language)){
            return redirect()->route('post.catalogue.index')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('post.catalogue.index')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id){
        $this->authorize('modules', 'post.catalogue.update');
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('messages.postCatalogue');
        $config['method'] = 'edit';
        $dropdown  = $this->nestedset->Dropdown();
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'postCatalogue',
        ));
    }

    public function update($id, UpdatePostCatalogueRequest $request){
        if($this->postCatalogueService->update($id, $request, $this->language)){
            return redirect()->route('post.catalogue.index')->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('post.catalogue.index')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id){
        $this->authorize('modules', 'post.catalogue.destroy');
        $config['seo'] = __('messages.postCatalogue');
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $template = 'backend.post.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'postCatalogue',
            'config',
        ));
    }

    public function destroy(DeletePostCatalogueRequest $request, $id){
        if($this->postCatalogueService->destroy($id, $this->language)){
            return redirect()->route('post.catalogue.index')->with('success','Xóa bản ghi thành công');
        }
        return redirect()->route('post.catalogue.index')->with('error','Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData(){
        return [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ]
          
        ];
    }

}
