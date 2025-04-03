<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
use App\Services\Interfaces\PostServiceInterface as PostService;
use App\Services\Interfaces\WidgetServiceInterface as WidgetService;
use App\Models\System;

class PostCatalogueController extends FrontendController
{
    protected $language;
    protected $system;
    protected $postCatalogueRepository;
    protected $postCatalogueService;
    protected $postService;
    protected $widgetService;

    public function __construct(
        PostCatalogueRepository $postCatalogueRepository,
        PostCatalogueService $postCatalogueService,
        PostService $postService,
        WidgetService $widgetService,
    ){
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->postCatalogueService = $postCatalogueService;
        $this->postService = $postService;
        $this->widgetService = $widgetService;
        parent::__construct(); 
    }


    public function index($id, $request, $page = 1){
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $breadcrumb = $this->postCatalogueRepository->breadcrumb($postCatalogue, $this->language);
        $posts = $this->postService->paginate(
            $request, 
            $this->language, 
            $postCatalogue, 
            $page,
            ['path' => $postCatalogue->canonical],
        );

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'post-catalogue-value', 'object' => true],
            ['keyword' => 'vision', 'object' => true],
            ['keyword' => 'post-catalogue-why', 'object' => true],
            ['keyword' => 'staff', 'object' => true],
        ], $this->language);


        if($postCatalogue->canonical == 've-chung-toi'){
            $template = 'frontend.post.catalogue.intro';
        }else if($postCatalogue->canonical == 'du-an-noi-bat'){
            $template = 'frontend.post.catalogue.project';
        }else{
            $template = 'frontend.post.catalogue.index';
        }

        $config = $this->config();
        $system = $this->system;
        $seo = seo($postCatalogue, $page);
        return view($template, compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'postCatalogue',
            'posts',
            'widgets',
        ));
    }


   

    private function config(){
        return [
            'language' => $this->language,
        ];
    }

}
