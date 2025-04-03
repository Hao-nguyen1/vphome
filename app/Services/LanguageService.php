<?php

namespace App\Services;

use App\Services\Interfaces\LanguageServiceInterface;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class LanguageService
 * @package App\Services
 */
class LanguageService implements LanguageServiceInterface
{
    protected $languageRepository;
    protected $routerRepository;
    

    public function __construct(
        LanguageRepository $languageRepository,
        RouterRepository $routerRepository,
    ){
        $this->languageRepository = $languageRepository;
        $this->routerRepository = $routerRepository;
    }

    

    public function paginate($request){

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perPage = $request->integer('perpage');
        $languages = $this->languageRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage, 
            ['path' => 'language/index'], 
        );
        return $languages;
    }

    public function create($request){
        DB::beginTransaction();
        try{
            $payload = $request->except(['_token','send']);
            $payload['user_id'] = Auth::id();
            $language = $this->languageRepository->create($payload);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }


    public function update($id, $request){
        DB::beginTransaction();
        try{

            $payload = $request->except(['_token','send']);
            $language = $this->languageRepository->update($id, $payload);
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
            $language = $this->languageRepository->delete($id);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    

    public function switch($id){
        DB::beginTransaction();
        try{
            $language = $this->languageRepository->update($id, ['current' => 1]);
            $payload = ['current' => 0];
            $where = [
                ['id', '!=', $id],
            ];
            $this->languageRepository->updateByWhere($where, $payload);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
      
    }

    public function saveTranslate($option, $request){
        DB::beginTransaction();
        try{
            $payload = [
                'name' => $request->input('translate_name'),
                'description' => $request->input('translate_description'),
                'content' => $request->input('translate_content'),
                'meta_title' => $request->input('translate_meta_title'),
                'meta_keyword' => $request->input('translate_meta_keyword'),
                'meta_description' => $request->input('translate_meta_description'),
                'canonical' => $request->input('translate_canonical'),
                $this->converModelToField($option['model']) => $option['id'],
                'language_id' => $option['languageId']
            ];
            $controllerName = $option['model'].'Controller';
            $repositoryNamespace = '\App\Repositories\\' . ucfirst($option['model']) . 'Repository';
            if (class_exists($repositoryNamespace)) {
                $repositoryInstance = app($repositoryNamespace);
            }
            $model = $repositoryInstance->findById($option['id']);
            $model->languages()->detach([$option['languageId'], $model->id]);
            $repositoryInstance->createPivot($model, $payload,'languages');

            $this->routerRepository->forceDeleteByCondition(
                [
                    ['module_id', '=', $option['id']],
                    ['controllers', '=', 'App\Http\Controllers\Frontend\\'.$controllerName],
                    ['language_id', '=', $option['languageId']]
                ]
            );
            $router = [
                'canonical' => Str::slug($request->input('translate_canonical')),
                'module_id' => $model->id,
                'language_id' => $option['languageId'],
                'controllers' => 'App\Http\Controllers\Frontend\\'.$controllerName.'',
            ];
            $this->routerRepository->create($router);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    private function converModelToField($model){
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
        return $temp.'_id';
    }

  
    private function paginateSelect(){
        return [
            'id', 
            'name', 
            'canonical',
            'publish',
            'image'
        ];
    }


}
