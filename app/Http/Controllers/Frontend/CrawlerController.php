<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface  as ProductCatalogueRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface  as ProductRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface  as RouterRepository;


class CrawlerController extends FrontendController
{

    protected $productCatalogueRepository;
    protected $productRepository;
    protected $routerRepository;

    public function __construct(
        ProductCatalogueRepository $productCatalogueRepository,
        ProductRepository $productRepository,
        RouterRepository $routerRepository,
    ){
       $this->productCatalogueRepository = $productCatalogueRepository;
       $this->productRepository = $productRepository;
       $this->routerRepository = $routerRepository;
       
    }

    public function index(Request $request){

        DB::beginTransaction();
        try{

            $url = 'https://vinamart24h.vn/';
            echo 'Đang tiến hành crawler danh mục sản phẩm';
            $html = file_get_contents($url);
            $crawler = new Crawler($html);
            $category = $crawler->filter('#col-213785515 .product-category');
            $tempTranslate = [];
            $temp  = [];
            if(count($category)){
                foreach($category as $key => $val){
                    $newCrawler = new Crawler($val);
                    $tempTranslate[] = [
                        'name' => $newCrawler->filter('.header-title')->text(),
                        'url' => $newCrawler->filter('a')->attr('href'),
                        'language_id' => 1,
                    ];
                    $temp[] = [
                        'parent_id' => 0,
                        'lft' => 0,
                        'rgt' => 0,
                        'level' => 1,
                        'publish' => 2,
                        'user_id' => Auth::id(),
                        'image' => ($newCrawler->filter('img')->extract(['src'])[1]) ?? ''
                    ];
                }
            }
            foreach($temp as $key => $val){
                $productCatalogue = $this->productCatalogueRepository->create($val);
                $this->productCatalogueRepository->createPivot($productCatalogue, $tempTranslate[$key], 'languages');
            }
            $this->nestedset = new Nestedsetbie([
                'table' => 'product_catalogues',
                'foreignkey' => 'product_catalogue_id',
                'language_id' =>  1 ,
            ]);
            $this->nestedset->Get('level ASC, order ASC');
            $this->nestedset->Recursive(0, $this->nestedset->Set());
            $this->nestedset->Action();
    
            DB::commit();

            return redirect()->route('crawler.update');
            
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
        
    }

    public function crawlerUpdate(){

        DB::beginTransaction();
        try{
            
            $productCatalogue = $this->productCatalogueRepository->findByCondition([
                ['check', '=', 0]
            ], false, ['languages']);
    
            if(is_null($productCatalogue)){
    
                // echo 'Đã cập nhật dữ liệu danh mục xong. Đang chuyển sang tiến trình lấy sản phẩm của danh mục';
                // sleep(1);
        
                // return $this->crawlerProduct($request);

                return redirect()->route('crawler.product');
    
            }
    
            echo 'Đang tiến hành cập nhật danh mục: '.$productCatalogue->languages->first()->pivot->name;
    
            $url = $productCatalogue->languages->first()->pivot->url;
    
            $html = file_get_contents($url);
    
            $crawler = new Crawler($html);
    
            if($crawler->filter('.term-description')->count() > 0){
                $updateTranslate['description'] = $crawler->filter('.term-description')->html();
    
                $productCatalogue->languages()->updateExistingPivot(1, $updateTranslate);
            }
            
            $update['check'] = 1;

    
            $this->productCatalogueRepository->update($productCatalogue->id, $update);
    
            $router = [
                'canonical' => Str::slug($productCatalogue->languages->first()->pivot->name),
                'module_id' => $productCatalogue->id,
                'controllers' => 'App\Http\Controllers\Frontend\ProductCatalogueController',
                'language_id' => 1,
            ];        
    
            
            $this->routerRepository->create($router);
            DB::commit();
    
            echo '<meta http-equiv="refresh" content="0">';

            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }


    public function crawlerProduct(Request $request){

        DB::beginTransaction();
        try{
            $productCatalogue = $this->productCatalogueRepository->findByCondition([
                ['check', '=', 1]
            ], false, ['languages']);

            
    
            if(is_null($productCatalogue)){

                return redirect()->route('crawler.product.update');
    
            }
    
            echo 'Đang tiến hành crawler dữ liệu sản phẩm của danh mục: '. $productCatalogue->languages->first()->pivot->name.'<br>';
            
            $url = $productCatalogue->languages->first()->pivot->url;
            $page = ($request->input('page')) ? $request->input('page') : 1;
            echo 'Trang-'.$page;
            $html = @file_get_contents($url.'page/'.$page);


            if($html === false){
                $page = 1;
                $this->productCatalogueRepository->update($productCatalogue->id, ['check' => 0]);
                echo '<meta http-equiv="refresh" content="0; URL=http://127.0.0.1:8000/crawlerProduct?page='.$page.'">';
                die();
            }

            $crawler = new Crawler($html);
    
            $product = $crawler->filter('.product');
    
           
            $temp = [];
            $tempTranslate = [];
            $catalogue = [];

            foreach($product as $key => $val){
                $newCrawler = new Crawler($val);

                $tempTranslate[] = [
                    'name' => $newCrawler->filter('.product-title')->text(),
                    'url' => $newCrawler->filter('.product-title a')->attr('href'),
                    'language_id' => 1,
                    'canonical' => Str::slug($newCrawler->filter('.product-title')->text())
                ];

                $temp[] = [
                    'product_catalogue_id' => $productCatalogue->id,
                    'publish' => 2,
                    'user_id' => Auth::id(),
                ];

            }

            foreach($temp as $key => $val){

                $checkProductExists = DB::table('product_language')->where('canonical', '=', $tempTranslate[$key]['canonical'])->exists();
                if(!$checkProductExists){
                    $product = $this->productRepository->create($val); // thêm sản phẩm.. 
                    $this->productRepository->createPivot($product, $tempTranslate[$key], 'languages');
                    $this->productRepository->createPivot($product, ['product_catalogue_id' => $productCatalogue->id], 'product_catalogues');
                }
            }


            $updateCatalogue['check'] = 0;

            $this->productCatalogueRepository->update($productCatalogue->id, $updateCatalogue);

            DB::commit();
            $page += 1;
            echo '<meta http-equiv="refresh" content="0; URL=http://127.0.0.1:8000/crawlerProduct?page='.$page.'">';
          
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }


    }


    public function updateProduct(){


        DB::beginTransaction();
        try{


            
           
            $product = $this->productRepository->findByCondition([
                ['check', '=', 0]
            ], false, ['languages']);


            if(is_null($product)){
                die('Crawling successfully');
            }
    
            $productName = $product->languages->first()->pivot->name;
            $url = $product->languages->first()->pivot->url;

            echo 'Đang tiến hành cập nhật dữ liệu cho sản phẩm .....: '. $productName.'<br>';
            echo '....';
            echo $url; 

    
            $html = @file_get_contents($url);
    
            $crawler = new Crawler($html);
    
    
    
            if($crawler->filter('.product-page-price del')->count() > 0){
                $priceClass = '.product-page-price del';
            }else{
                $priceClass = '.product-page-price bdi';
            }
    
            $price = htmlspecialchars_decode(html_entity_decode($crawler->filter($priceClass)->text()));
            
            $price = (int)str_replace([',', '₫'], '', $price);
    
            $update = [
                'price' => $price,
            ];
            

            $updateTranslate = [
                'description' => $crawler->filter('.product-short-description')->html(),
            ];
    
    
            $imgContent = [];
            $imageBase64Content = [];
    
            $content = $crawler->filter('.entry-content');


            
    
            $productPath = public_path('userfiles/image/product/'. Str::slug($productName));
    
            
            if(!File::exists($productPath)){
                File::makeDirectory($productPath, $mode = 0777, true, true);
            }

            
            
            

          
            $contentCrawler = new Crawler($content->html());
            if($content->count() > 0){
                

                // $contentCrawler = new Crawler($content->html());

    
                $img = $contentCrawler->filter('img');

                if($img->count() > 0){


                    //get image content
                    $img->each(function(Crawler $node, $i) use (&$imgContent, &$imageBase64Content, $productPath, $productName, $img){
    
                        $imgSrc = $node->extract(['src']);
    
    
                        $filename = $imgSrc[0];
                        if(!empty($imgSrc)){
                            if(strpos($filename, 'data:image') === false){
    
                                $imgContent[] = $imgSrc[0];
                                $fileDownload = strtok($filename, '?');
                                $destination = $productPath. '/' . basename($fileDownload);
                                $imageStatus = @file_get_contents($fileDownload);
                                if($imageStatus != false){
                                    File::copy($fileDownload, $destination);
                                }
                            }else{
                                $imageBase64Content[] = $imgSrc[0];
                            }
                        }
                    });
                }

                // dd($imageBase64Content, $imgContent);


                $contentCrawler->filter('img')->each(function(Crawler $node, $i) use ($productName, $imgContent, &$temp){
                    if($i < count($imgContent)){
    
                        $filename = basename($imgContent[$i]);
    
                        $imageUrl = '/userfiles/image/product/'.Str::slug($productName).'/'.basename($filename);
                        $node->getNode(0)->setAttribute('src', $imageUrl);
                    }
    
                });
            }


    
            $updateContent = $contentCrawler->html();
            $updateTranslate['content'] = $updateContent;
            $album = $crawler->filter('.woocommerce-product-gallery__image');
    
            $imageAlbum = [];
            foreach($album as $key => $val){
                $albumCrawler = new Crawler($val);
                $file = $albumCrawler->filter('img')->extract(['src'])[0];
                $destinationPath = $productPath.'/'.basename($file);
                $imageAlbum[] = '/userfiles/image/product/'.Str::slug($productName).'/'.basename($file);
                File::copy($file, $destinationPath);
                
            }
            $update['album'] = json_encode($imageAlbum);
    
            $update['check'] = 1;
            $update['image'] = $imageAlbum[0];

            // dd($updateTranslate);
    
            $productModel = $this->productRepository->update($product->id, $update);
            $product->languages()->updateExistingPivot(1, $updateTranslate);
    
            $router = [
                'canonical' => Str::slug($productName),
                'module_id' => $product->id,
                'controllers' => 'App\Http\Controllers\Frontend\ProductController',
                'language_id' => 1,
            ];

            // dd($router);
    
            $this->routerRepository->create($router);
    
            DB::commit();
            echo '<meta http-equiv="refresh" content="1">'; 
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

}
