<?php

namespace App\Services;

use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{


    protected $postCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $language;
    protected $controllerName = 'PostCatalogueController';
    

    public function __construct(
        PostCatalogueRepository $postCatalogueRepository,
        RouterRepository $routerRepository,
    ){
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->routerRepository = $routerRepository;
        // $this->nestedset = new Nestedsetbie([
        //     'table' => 'post_catalogues',
        //     'foreignkey' => 'post_catalogue_id',
        //     'language_id' =>   ,
        // ]);
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
        $postCatalogues = $this->postCatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'post.catalogue.index'],  
            ['post_catalogues.lft', 'ASC'],
            [
                ['post_catalogue_language as tb2','tb2.post_catalogue_id', '=' , 'post_catalogues.id']
            ], 
            ['languages']
        );

        return $postCatalogues;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $postCatalogue = $this->createCatalogue($request);
            if($postCatalogue->id > 0){
                $this->updateLanguageForCatalogue($postCatalogue, $request, $languageId);
                $this->createRouter($postCatalogue, $request, $this->controllerName, $languageId);
                $this->nestedset = new Nestedsetbie([
                    'table' => 'post_catalogues',
                    'foreignkey' => 'post_catalogue_id',
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
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($postCatalogue, $request);
            if($flag == TRUE){
                $this->updateLanguageForCatalogue($postCatalogue, $request, $languageId);
                $this->updateRouter(
                    $postCatalogue, $request, $this->controllerName, $languageId
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => 'post_catalogues',
                    'foreignkey' => 'post_catalogue_id',
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
            $postCatalogue = $this->postCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\PostCatalogueController'],
            ]);

            $this->nestedset = new Nestedsetbie([
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
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
        $postCatalogue = $this->postCatalogueRepository->create($payload);
        return $postCatalogue;
    }

    private function updateCatalogue($postCatalogue, $request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->postCatalogueRepository->update($postCatalogue->id, $payload);
        return $flag;
    }

    private function updateLanguageForCatalogue($postCatalogue, $request, $languageId){
        $payload = $this->formatLanguagePayload($postCatalogue, $request, $languageId);
        $postCatalogue->languages()->detach([$languageId, $postCatalogue->id]);
        $language = $this->postCatalogueRepository->createPivot($postCatalogue, $payload, 'languages');
        return $language;
    }

    private function formatLanguagePayload($postCatalogue, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['post_catalogue_id'] = $postCatalogue->id;
        return $payload;
    }


    private function paginateSelect(){
        return [
            'post_catalogues.id', 
            'post_catalogues.publish',
            'post_catalogues.image',
            'post_catalogues.level',
            'post_catalogues.order',
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
