<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface  as MenuCatalogueRepository;
use App\Services\Interfaces\MenuCatalogueServiceInterface  as MenuCatalogueService;
use App\Services\Interfaces\MenuServiceInterface  as MenuService;
use App\Repositories\Interfaces\MenuRepositoryInterface  as MenuRepository;
use App\Models\Language;
use App\Http\Requests\Menu\StoreMenuCatalogueRequest;


class MenuController extends Controller
{
    protected $menuCatalogueRepository;
    protected $menuCatalogueService;
    protected $menuService;
    protected $menuRepository;
    protected $language;

    public function __construct(
        MenuCatalogueRepository $menuCatalogueRepository,
        MenuCatalogueService $menuCatalogueService,
        MenuService $menuService,
        MenuRepository $menuRepository,
    ){
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuCatalogueService = $menuCatalogueService;
        $this->menuService = $menuService;
        $this->menuRepository = $menuRepository;
        $this->middleware(function($request, $next){
            $locale = app()->getLocale(); // vn en cn
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function createCatalogue(StoreMenuCatalogueRequest $request){
        $menuCatalogue = $this->menuCatalogueService->create($request);
        if($menuCatalogue !== FALSE){
            return response()->json([
                'code' => 0,
                'message' => 'Tạo nhóm menu thành công!',
                'data' => $menuCatalogue,
            ]);
        }
        return response()->json([
            'message' => 'Có vấn đề xảy ra, hãy thử lại',
            'code' => 1
        ]);
    }

    public function drag(Request $request){
        $json = json_decode($request->string('json'), TRUE);
        $menuCatalogueId = $request->integer('menu_catalogue_id');

        $flag = $this->menuService->dragUpdate($json, $menuCatalogueId, $this->language);
    }
    
    public function deleteMenu(Request $request){
        $id = $request->input('menu_id');
        $this->menuService->destroyMenu($id, $this->language);
    }
    
}
