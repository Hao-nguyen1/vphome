<?php

namespace App\Services;

use App\Services\Interfaces\ProductCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class ProductCatalogueService
 * @package App\Services
 */
class ProductCatalogueService extends BaseService implements ProductCatalogueServiceInterface
{


    protected $productCatalogueRepository;
    protected $attributeCatalogueRepository;
    protected $attributeRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $language;
    protected $controllerName = 'ProductCatalogueController';
    

    public function __construct(
        ProductCatalogueRepository $productCatalogueRepository,
        AttributeCatalogueRepository $attributeCatalogueRepository,
        AttributeRepository $attributeRepository,
        RouterRepository $routerRepository,
    ){
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->attributeRepository = $attributeRepository;
        $this->routerRepository = $routerRepository;
    }

    public function paginate($request, $languageId){
        $perPage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId]
            ]
        ];
        $productCatalogues = $this->productCatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'product/catalogue/index'],  
            ['product_catalogues.lft', 'ASC'],
            [
                ['product_catalogue_language as tb2','tb2.product_catalogue_id', '=' , 'product_catalogues.id']
            ], 
            ['languages']
        );

        return $productCatalogues;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $productCatalogue = $this->createCatalogue($request);
            if($productCatalogue->id > 0){
                $this->updateLanguageForCatalogue($productCatalogue, $request, $languageId);
                $this->createRouter($productCatalogue, $request, $this->controllerName, $languageId);
                $this->nestedset = new Nestedsetbie([
                    'table' => 'product_catalogues',
                    'foreignkey' => 'product_catalogue_id',
                    'language_id' =>  $languageId ,
                ]);
                $this->nestedset();
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    public function update($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $productCatalogue = $this->productCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($productCatalogue, $request);
            if($flag == TRUE){
                $this->updateLanguageForCatalogue($productCatalogue, $request, $languageId);
                $this->updateRouter(
                    $productCatalogue, $request, $this->controllerName, $languageId
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => 'product_catalogues',
                    'foreignkey' => 'product_catalogue_id',
                    'language_id' =>  $languageId ,
                ]);
                $this->nestedset();
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    public function destroy($id, $languageId){
        DB::beginTransaction();
        try{
            $productCatalogue = $this->productCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\ProductCatalogueController'],
            ]);
            $this->nestedset = new Nestedsetbie([
                'table' => 'product_catalogues',
                'foreignkey' => 'product_catalogue_id',
                'language_id' =>  $languageId ,
            ]);
            $this->nestedset();
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    private function createCatalogue($request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $payload['user_id'] = Auth::id();
        $productCatalogue = $this->productCatalogueRepository->create($payload);
        return $productCatalogue;
    }

    private function updateCatalogue($productCatalogue, $request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->productCatalogueRepository->update($productCatalogue->id, $payload);
        return $flag;
    }

    private function updateLanguageForCatalogue($productCatalogue, $request, $languageId){
        $payload = $this->formatLanguagePayload($productCatalogue, $request, $languageId);
        $productCatalogue->languages()->detach([$languageId, $productCatalogue->id]);
        $language = $this->productCatalogueRepository->createPivot($productCatalogue, $payload, 'languages');
        return $language;
    }

    private function formatLanguagePayload($productCatalogue, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['product_catalogue_id'] = $productCatalogue->id;
        return $payload;
    }


    public function setAttribute($product){
        $attribute = $product->attribute;
        $result = null;
        if(!is_null($attribute)){
            $productCatalogueId = (int)$product->product_catalogue_id;
            $productCatalogue = $this->productCatalogueRepository->findById($productCatalogueId);
            if(!is_array($productCatalogue->attribute)){
                $payload['attribute'] = $attribute;
            }else{
                $mergeArray = $productCatalogue->attribute;
                foreach($attribute as $key => $val){
                    if(!isset($mergeArray[$key])){
                        $mergeArray[$key] = $val;
                    }else{
                        $mergeArray[$key] = array_values(array_unique(array_merge($mergeArray[$key], $val)));
                    }
                }
                $flatAttributeArray = array_merge(...$mergeArray);
                $attributeList = $this->attributeRepository->findAttributeProductVariant($flatAttributeArray, $productCatalogue->id);
    
                $payload['attribute'] = array_map(function($newArray) use ($attributeList){
                        return  array_intersect($newArray, $attributeList->all());
    
                }, $mergeArray);
    
            }
            $result = $this->productCatalogueRepository->update($productCatalogueId, $payload);
        }
        return $result;
    }

  

    public function getFilterList(array $attribute = [], $languageId){
        $attributeCatalougeId = array_keys($attribute);
        $attributeId = array_unique(array_merge(...$attribute));


        $attributeCatalogues = $this->attributeCatalogueRepository->findByCondition(
            [
                config('apps.general.defaultPublish')
            ],
            true, 
            [
                'languages' => function($query) use ($languageId){
                    $query->where('language_id', $languageId);
                }
            ], 
            ['id', 'asc'], 
            [
                'whereIn' => $attributeCatalougeId, 
                'whereInField' => 'id'
            ]
        );

        $attributes = $this->attributeRepository->findByCondition(
            [
                config('apps.general.defaultPublish')
            ],
            true, 
            [
                'languages' => function($query) use ($languageId){
                    $query->where('language_id', $languageId);
                    // $query->first();
                }
            ], 
            ['id', 'asc'], 
            [
                'whereIn' => $attributeId, 
                'whereInField' => 'id'
            ]
        );

        foreach($attributeCatalogues as $key => $val){
            $attributeItem = [];
            foreach($attributes as $index => $item){
                if($item->attribute_catalogue_id === $val->id){
                    $attributeItem[] = $item;
                }
            }
            $val->setAttribute('attributes', $attributeItem);
        }
        return $attributeCatalogues;
    }
    
    

    private function paginateSelect(){
        return [
            'product_catalogues.id', 
            'product_catalogues.publish',
            'product_catalogues.image',
            'product_catalogues.level',
            'product_catalogues.order',
            'tb2.name', 
            'tb2.canonical',
        ];
    }

    private function payload(){
        return [
            'parent_id',
            'follow',
            'publish',
            'image',
            'album',
            'icon',
        ];
    }
    private function payloadLanguage(){
        return [
            'name',
            'description',
            'content',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'canonical'
        ];
    }


}
