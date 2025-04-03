<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Services\Interfaces\ProductCatalogueServiceInterface as ProductCatalogueService;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\ReviewRepositoryInterface as ReviewRepository;
use App\Services\Interfaces\WidgetServiceInterface  as WidgetService;
use App\Models\System;
use Cart;

class ProductController extends FrontendController
{
    protected $language;
    protected $system;
    protected $productCatalogueRepository;
    protected $productCatalogueService;
    protected $productService;
    protected $productRepository;
    protected $reviewRepository;
    protected $widgetService;

    public function __construct(
        ProductCatalogueRepository $productCatalogueRepository,
        ProductCatalogueService $productCatalogueService,
        ProductService $productService,
        ProductRepository $productRepository,
        ReviewRepository $reviewRepository,
        WidgetService $widgetService,
    ){
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productCatalogueService = $productCatalogueService;
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->reviewRepository = $reviewRepository;
        $this->widgetService = $widgetService;
        parent::__construct(); 
    }


    public function index($id, $request){
        $language = $this->language;
        $product = $this->productRepository->getProductById($id, $this->language, config('apps.general.defaultPublish'));
        if(is_null($product)){
            abort(404);
        }
        $product = $this->productService->combineProductAndPromotion([$id], $product, true);
        

        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($product->product_catalogue_id, $this->language);
        $breadcrumb = $this->productCatalogueRepository->breadcrumb($productCatalogue, $this->language);
        /* ------------------- */
        $product = $this->productService->getAttribute($product, $this->language);
        $category = recursive(
            $this->productCatalogueRepository->all([
                'languages' => function($query) use ($language){
                    $query->where('language_id', $language);
                }
            ], categorySelectRaw('product'))
        );

        $wishlist = Cart::instance('wishlist')->content();

        $widgets = $this->widgetService->getWidget([
            // ['keyword' => 'category', 'countObject' => true],
            // ['keyword' => 'homepage-customer', 'children' => true],
            // ['keyword' => 'category-highlight'],
            // ['keyword' => 'product', 'children' => true, 'promotion' => TRUE, 'object' => TRUE],
            ['keyword' => 'products-hl','promotion' => true],
            // ['keyword' => 'home-intro'],
            // ['keyword' => 'home-project', 'object' => true],
            // ['keyword' => 'home-video', 'object' => true],
            // ['keyword' => 'home-whyus', 'object' => true],
            // ['keyword' => 'posts', 'object' => true],
        ], $this->language);


       


        $productSeen = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'qty' => 1,
            'options' => [
                'canonical' => $product->languages->first()->pivot->canonical,
                'image' => $product->image,
            ]
        ];

        // Cart::instance('seen')->destroy();

        
        Cart::instance('seen')->add($productSeen);

        $cartSeen = Cart::instance('seen')->content();



        $config = $this->config();
        $system = $this->system;
        $seo = seo($product);
        return view('frontend.product.product.index', compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'productCatalogue',
            'product',
            'category',
            'widgets',
            'wishlist',
            'cartSeen',
        ));
    }

    private function config(){
        return [
            'language' => $this->language,
            'js' => [
                'frontend/core/library/cart.js',
                'frontend/core/library/product.js',
                'frontend/core/library/review.js'
            ],
            'css' => [
                'frontend/core/css/product.css',
            ]
        ];
    }

}
