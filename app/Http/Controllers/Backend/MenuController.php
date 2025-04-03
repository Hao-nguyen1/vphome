<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\MenuServiceInterface  as MenuService;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Models\Language;
use App\Http\Requests\Menu\StoreMenuRequest;
use App\Http\Requests\Menu\StoreMenuChildrenRequest;
use App\Http\Requests\Menu\UpdateMenuRequest;

class MenuController extends Controller
{
    protected $menuService;
    protected $menuRepository;
    protected $menuCatalogueRepository;
    protected $menuCatalogueService;
    protected $languageRepository;

    public function __construct(
        MenuService $menuService,
        MenuRepository $menuRepository,
        MenuCatalogueRepository $menuCatalogueRepository,
        MenuCatalogueService $menuCatalogueService,
        LanguageRepository $languageRepository,
    ){
        $this->menuService = $menuService;
        $this->menuRepository = $menuRepository;
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuCatalogueService = $menuCatalogueService;
        $this->languageRepository = $languageRepository;
        $this->middleware(function($request, $next){
            $locale = app()->getLocale(); // vn en cn
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request){
        $this->authorize('modules', 'menu.index');
        $menuCatalogues = $this->menuCatalogueService->paginate($request, 1);
      
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'MenuCatalogue'
        ];
        $config['seo'] = __('messages.menu');
        $template = 'backend.menu.menu.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuCatalogues'
        ));
    }

    public function create(){
        $this->authorize('modules', 'menu.create');
        $menuCatalogues = $this->menuCatalogueRepository->all();
        $config = $this->config();
        $config['seo'] = __('messages.menu');
        $config['method'] = 'create';
        $template = 'backend.menu.menu.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuCatalogues',
        ));
    }

    public function store(StoreMenuRequest $request){
        if($this->menuService->save($request, $this->language)){
            $menuCatalogueId = $request->input('menu_catalogue_id');
            return redirect()->route('menu.edit', ['id' => $menuCatalogueId])->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('menu.index')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id){
        $this->authorize('modules', 'menu.update');
        $language = $this->language;

        $menus = $this->menuRepository->findByCondition([
            ['menu_catalogue_id', '=', $id]
        ], TRUE, [
            'languages' => function($query) use ($language) {
                $query->where('language_id',  $language);
            }
        ], ['order', 'DESC']);

        $menuCatalogue = $this->menuCatalogueRepository->findById($id);


        $config = $this->config();
        $config['seo'] = __('messages.menu');
        $config['method'] = 'edit';
        $template = 'backend.menu.menu.show';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menus',
            'id',
            'menuCatalogue',
        ));
    }

    public function editMenu($id){
        $this->authorize('modules', 'menu.update');
        $language = $this->language;
        $menus = $this->menuRepository->findByCondition([
            ['menu_catalogue_id', '=', $id],
            ['parent_id', '=', 0],
        ], TRUE, [
            'languages' => function($query) use ($language) {
                $query->where('language_id',  $language);
            }
        ], ['order', 'DESC']);
        $menuList = $this->menuService->convertMenu($menus);
        $menuCatalogues = $this->menuCatalogueRepository->all();
        $menuCatalogue = $this->menuCatalogueRepository->findById($id);
        $config = $this->config();
        $config['seo'] = __('messages.menu');
        $config['method'] = 'update';
        $template = 'backend.menu.menu.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuList',
            'menuCatalogues',
            'menuCatalogue',
            'id',
        ));
    }

    public function delete($id){
        $this->authorize('modules', 'menu.destroy');
        $config['seo'] = __('messages.menu');
        $menuCatalogue = $this->menuCatalogueRepository->findById($id);
        $template = 'backend.menu.menu.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuCatalogue',
        ));
    }

    public function destroy($id){
        if($this->menuService->destroy($id)){
            return redirect()->route('menu.index')->with('success','Xóa bản ghi thành công');
        }
        return redirect()->route('menu.index')->with('error','Xóa bản ghi không thành công. Hãy thử lại');
    }

    public function children($id){
        $this->authorize('modules', 'menu.create');
        $language = $this->language;
        $menuBread = $this->menuRepository->findById($id, ['*'], [
            'languages' => function($query) use ($language) {
                $query->where('language_id',  $language);
            }
        ]);
        $menuList = $this->menuService->getAndConvertMenu($menuBread, $this->language);
        $config = $this->config();
        $config['seo'] = __('messages.menu');
        $config['method'] = 'children';
        $template = 'backend.menu.menu.children';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuBread',
            'menuList',
        ));
    }

   
    public function saveChildren(StoreMenuChildrenRequest $request, $id){
        $menu = $this->menuRepository->findById($id);
        if($this->menuService->saveChildren($request, $this->language, $menu)){
            return redirect()->route('menu.edit', ['id' => $menu->menu_catalogue_id])->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('menu.edit', ['id' => $menu->menu_catalogue_id])->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function translate(int $languageId = 1, int $id = 0){

        $language = $this->languageRepository->findById($languageId);
        $menuCatalogue = $this->menuCatalogueRepository->findById($id);

        $currentLanguage = $this->language;
        $menus = $this->menuRepository->findByCondition([
            ['menu_catalogue_id', '=', $id],
        ], TRUE, [
            'languages' => function($query) use ($currentLanguage) {
                $query->where('language_id',  $currentLanguage);
            }
        ], ['lft', 'asc']);
        
        $menus = buildMenu($this->menuService->findMenuItemTranslate($menus, $currentLanguage, $languageId));
        // dd($menus);
        $config['seo'] = __('messages.menu');
        $config['method'] = 'translate';
        $template = 'backend.menu.menu.translate';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'languageId',
            'language',
            'menuCatalogue',
            'menus',
        ));
    }

    public function saveTranslate(Request $request, $languageId = 1){
        if($this->menuService->saveTranslateMenu($request, $languageId)){
            return redirect()->route('menu.index')->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('menu.index')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    private function config(){
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/menu.js',
                'backend/js/plugins/nestable/jquery.nestable.js'
                
            ]
        ];
    }

}
