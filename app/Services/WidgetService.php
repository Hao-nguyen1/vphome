<?php

namespace App\Services;

use App\Services\Interfaces\WidgetServiceInterface;
use App\Repositories\Interfaces\WidgetRepositoryInterface as WidgetRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class WidgetService
 * @package App\Services
 */
class WidgetService extends BaseService implements WidgetServiceInterface 
{
    protected $widgetRepository;
    protected $promotionRepository;
    protected $ProductCatalogueRepository;
    protected $ProductService;
    

    public function __construct(
        WidgetRepository $widgetRepository,
        PromotionRepository $promotionRepository,
        ProductCatalogueRepository $productCatalogueRepository,
        ProductService $productService,
    ){
        $this->widgetRepository = $widgetRepository;
        $this->promotionRepository = $promotionRepository;
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productService = $productService;
    }

    

    public function paginate($request){
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perPage = $request->integer('perpage');
        $widgets = $this->widgetRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'widget/index'], 
        );
        
        return $widgets;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $payload = $request->only('name', 'keyword', 'short_code', 'description', 'album', 'model');
            $payload['model_id'] = $request->input('modelItem.id');
            $payload['description'] = [
                $languageId => $payload['description']
            ];
            $widget = $this->widgetRepository->create($payload);
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

            $payload = $request->only('name', 'keyword', 'short_code', 'description', 'album', 'model');
            $payload['model_id'] = $request->input('modelItem.id');
            $payload['description'] = [
                $languageId => $payload['description']
            ];

            $widget = $this->widgetRepository->update($id, $payload);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    public function destroy($id){
        DB::beginTransaction();
        try{
            $widget = $this->widgetRepository->delete($id);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    public function saveTranslate($request, $languageId){
        DB::beginTransaction();
        try{
            $temp = [];
            $translateId = $request->input('translateId');
            $widget =  $this->widgetRepository->findById($request->input('widgetId'));
            $temp = $widget->description;
            $temp[$translateId] = $request->input('translate_description');
            $payload['description'] = $temp;
            
            $this->widgetRepository->update($widget->id, $payload);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }
   
    
    private function paginateSelect(){
        return [
            'id', 
            'name', 
            'keyword',
            'short_code', 
            'publish',
            'description',
        ];
    }


    /* FRONTEND SERVICE */
    


    public function getWidget(array $params = [], int $language){
        $whereIn = [];
        $whereInField = 'keyword';
        if(count($params)){
            foreach($params as $key => $val){
                $whereIn[] = $val['keyword'];
            }
        }
        $widgets = $this->widgetRepository->getWidgetWhereIn($whereIn);
        if(!is_null($widgets)){
            $temp = [];
            foreach($widgets as $key => $widget){
                $class = loadClass($widget->model);
                $agrument = $this->widgetAgrument($widget, $language, $params[$key]);
                $object = $class->findByCondition(...$agrument);
                $model = lcfirst(str_replace('Catalogue','', $widget->model)); 
                $replace = $model.'s';
                $service = $model.'Service';
                if(count($object) && strpos($widget->model, 'Catalogue')){
                    $classRepo = loadClass( ucfirst($model) );
                    foreach($object as $objectKey => $objectValue){
                        if(isset($params[$key]['children']) && $params[$key]['children']){
                            $childrenAgrument = $this->childrenAgrument([$objectValue->id], $language);
                            $objectValue->childrens = $class->findByCondition(...$childrenAgrument);
                        }

                         // ---------- LẤY SẢN PHẨM ---------------//
                        $childId = $class->recursiveCategory($objectValue->id, $model);
                        // dd($childId);
                        // dd($childId);
                        $ids = [];
                        foreach($childId as $child_id){
                            $ids[] = $child_id->id;
                        }
                        
                        $objectValue->{$replace} = $classRepo->findObjectByCategoryIds($ids, $model, $language);

                        if(
                            isset($params[$key]['promotion']) 
                            && 
                            $params[$key]['promotion'] == true 
                        ){
                            $productId = $objectValue->{$replace}->pluck('id')->toArray();
                            $objectValue->{$replace} = $this->{$service}->combineProductAndPromotion($productId, $objectValue->{$replace});
                        }
                        $widgets[$key]->object = $object;
                    }  
                }else{
                    if(
                        isset($params[$key]['promotion']) 
                        && 
                        $params[$key]['promotion'] == true 
                    ){
                        $productId = $object->pluck('id')->toArray();
                        $object = $this->{$service}->combineProductAndPromotion($productId, $object);
                    }
                    $widget->object = $object;
                }
                $temp[$widget->keyword] = $widgets[$key];
            }
        }
        return $temp;
    }

    private function childrenAgrument($objectId, $language){
        return [
            'condition' => [
                config('apps.general.defaultPublish')
            ],
            'flag' => true,
            'relation' => [
                'languages' => function($query) use($language){
                    $query->where('language_id', $language);
                }
            ],
            'param' => [
                'whereIn' => $objectId,
                'whereInField' => 'parent_id'
            ]
        ];
    }

    private function widgetAgrument($widget, $language, $param){
        $relation = [
            'languages' => function($query) use ($language) {
                $query->where('language_id', $language);
            }
        ];
        $withCount = [];
        if(strpos($widget->model, 'Catalogue')){
            $model = lcfirst(str_replace('Catalogue','', $widget->model)).'s';
            if(isset($param['object'])){
                $relation[$model] = function($query) use ($param, $language){
                    $query->whereHas('languages', function($query) use ($language){
                        $query->where('language_id', $language);
                    });
                    $query->take(($param['limit']) ?? 10);
                    $query->orderBy('order', 'desc');
                };
            }
            if(isset($param['countObject'])){
                $withCount[] = $model;
            }
            
        }else{
            $model = lcfirst($widget->model).'_catalogues';
            $relation[$model] = function($query) use ($language){
                $query->with('languages', function($query) use ($language){
                    $query->where('language_id', $language);
                });
            };
        }
        return [
            'condition' => [
                config('apps.general.defaultPublish')
            ],
            'flag' =>  true,
            'relation' => $relation,
            'param' => [
                'whereIn' => $widget->model_id,
                'whereInField' => 'id'
            ],
            'withCount' => $withCount,
        ];
    }

   

}