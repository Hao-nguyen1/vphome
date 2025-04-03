<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Services\Interfaces\ProductCatalogueServiceInterface as ProductCatalogueService;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Models\System;
use Cart;

class ProductCatalogueController extends FrontendController
{
    protected $language;
    protected $system;
    protected $productCatalogueRepository;
    protected $productCatalogueService;
    protected $productService;
    protected $widgetService;
    protected $productRepository;

    public function __construct(
        ProductCatalogueRepository $productCatalogueRepository,
        ProductCatalogueService $productCatalogueService,
        ProductService $productService,
        ProductRepository $productRepository,
        WidgetService $widgetService,
    ){
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productCatalogueService = $productCatalogueService;
        $this->productService = $productService;
        $this->widgetService = $widgetService;
        $this->productRepository = $productRepository;
        parent::__construct(); 
    }


    public function index($id, $request, $page = 1){
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);

        $filters = $this->filter($productCatalogue);

        $breadcrumb = $this->productCatalogueRepository->breadcrumb($productCatalogue, $this->language);
        
        $products = $this->productService->paginate(
            $request, 
            $this->language, 
            $productCatalogue, 
            $page,
            ['path' => $productCatalogue->canonical],
        );

        $productId = $products->pluck('id')->toArray();

        if(count($productId) && !is_null($productId)){
            $products = $this->productService->combineProductAndPromotion($productId, $products);
        }


        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'products-hl','promotion' => true],
        ], $this->language);

        $config = $this->config();
        $system = $this->system;
        $seo = seo($productCatalogue, $page);
        return view('frontend.product.catalogue.index', compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'productCatalogue',
            'products',
            'filters',
            'widgets',
        ));
    }

    private function filter($productCatalogue){
        $filters = null;
        
        
        $children = $this->productCatalogueRepository->getChildren($productCatalogue);
        $groupedAttributes = [];
        foreach ($children as $child) {
            if (isset($child->attribute) && !is_null($child->attribute) && count($child->attribute)) {
                foreach ($child->attribute as $key => $value) {
                    if (!isset($groupedAttributes[$key])) {
                        $groupedAttributes[$key] = [];
                    }
                    $groupedAttributes[$key][] = $value;
                }
            }
        }
        foreach ($groupedAttributes as $key => $value) {
            $groupedAttributes[$key] = array_merge(...$value);
        }

        if(isset($groupedAttributes) && !is_null($groupedAttributes) &&  count($groupedAttributes)){
            $filters = $this->productCatalogueService->getFilterList($groupedAttributes, $this->language);
        }
        return $filters;
    }

    
    public function search(Request $request){

        $products = $this->productRepository->search($request->input('keyword'), $this->language);

        $productId = $products->pluck('id')->toArray();
        if(count($productId) && !is_null($productId)){
            $products = $this->productService->combineProductAndPromotion($productId, $products);
        }

        $config = $this->config();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Tìm kiếm cho từ khóa: '.$request->input('keyword'),
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('tim-kiem')
        ];
        return view('frontend.product.catalogue.search', compact(
            'config',
            'seo',
            'system',
            'products',
        ));
    }

    public function wishlist(Request $request){

        $id = Cart::instance('wishlist')->content()->pluck('id')->toArray();

        $products = $this->productRepository->wishlist($id, $this->language);
        $productId = $products->pluck('id')->toArray();
        if(count($productId) && !is_null($productId)){
            $products = $this->productService->combineProductAndPromotion($productId, $products);
        }

        $config = $this->config();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Danh sách yêu thích',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('tim-kiem')
        ];
        return view('frontend.product.catalogue.search', compact(
            'config',
            'seo',
            'system',
            'products',
        ));
    }
  

    private function config(){
        return [
            'language' => $this->language,
            'externalJs' => [
                '//code.jquery.com/ui/1.11.4/jquery-ui.js'
            ],
            'js' => [
                'frontend/core/library/filter.js',
            ],
           
        ];
    }

}
