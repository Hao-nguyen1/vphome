<?php

namespace App\Services;

use App\Services\Interfaces\PostServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class PostService
 * @package App\Services
 */
class PostService extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    protected $routerRepository;
    
    public function __construct(
        PostRepository $postRepository,
        RouterRepository $routerRepository,
    ){
        $this->postRepository = $postRepository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = 'PostController';
    }

    private function whereRaw($request, $languageId, $postCatalogue = null){
        $rawCondition = [];
        if($request->integer('post_catalogue_id') > 0 || !is_null($postCatalogue)){
            $catId = ($request->integer('post_catalogue_id') > 0) ? $request->integer('post_catalogue_id') : $postCatalogue->id;
            $rawCondition['whereRaw'] =  [
                [
                    'tb3.post_catalogue_id IN (
                        SELECT id
                        FROM post_catalogues
                        JOIN post_catalogue_language ON post_catalogues.id = post_catalogue_language.post_catalogue_id
                        WHERE lft >= (SELECT lft FROM post_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM post_catalogues as pc WHERE pc.id = ?)
                        AND post_catalogue_language.language_id = '.$languageId.'
                    )',
                    [$catId, $catId]
                ]
            ];
            
        }
        return $rawCondition;
    }

    public function paginate($request, $languageId, $postCatalogue = null, $page = 1, $extend = []){
        
        // if(!is_null($postCatalogue)){
        //     Paginator::currentPageResolver(function () use ($page) {
        //         return $page;
        //     });
        // }
     
        $perPage = (!is_null($postCatalogue))  ? 15 : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId],
            ],
        ];

        $paginationConfig = [
            'path' => ($extend['path']) ?? 'post/index', 
            'groupBy' => $this->paginateSelect()
        ];


        $orderBy = ['posts.id', 'DESC'];
        $relations = ['post_catalogues'];
        $rawQuery = $this->whereRaw($request, $languageId, $postCatalogue);

        $joins = [
            ['post_language as tb2', 'tb2.post_id', '=', 'posts.id'],
            ['post_catalogue_post as tb3', 'posts.id', '=', 'tb3.post_id'],
        ];

        $posts = $this->postRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            $paginationConfig,  
            $orderBy,
            $joins,  
            $relations,
            $rawQuery
        ); 



        return $posts;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $post = $this->createPost($request);
            if($post->id > 0){
                $this->updateLanguageForPost($post, $request, $languageId);
                $this->updateCatalogueForPost($post, $request);
                $this->createRouter($post, $request, $this->controllerName, $languageId);
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
            $post = $this->postRepository->findById($id);
            if($this->uploadPost($post, $request)){
                $this->updateLanguageForPost($post, $request, $languageId);
                $this->updateCatalogueForPost($post, $request);
                $this->updateRouter(
                    $post, $request, $this->controllerName, $languageId
                );
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

    public function destroy($id){
        DB::beginTransaction();
        try{
            $post = $this->postRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\PostController'],
            ]);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            // echo $e->getMessage();die();
            return false;
        }
    }

    private function createPost($request){
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($request);
        $post = $this->postRepository->create($payload);
        return $post;
    }

    private function uploadPost($post, $request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        return $this->postRepository->update($post->id, $payload);
    }

    private function updateLanguageForPost($post, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $post->id, $languageId);
        $post->languages()->detach([$languageId, $post->id]);
        return $this->postRepository->createPivot($post, $payload, 'languages');
    }

    private function updateCatalogueForPost($post, $request){
        $post->post_catalogues()->sync($this->catalogue($request));
    }

    private function formatLanguagePayload($payload, $postId, $languageId){
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['post_id'] = $postId;
        return $payload;
    }


    private function catalogue($request){
        if($request->input('catalogue') != null){
            return array_unique(array_merge($request->input('catalogue'), [$request->post_catalogue_id]));
        }
        return [$request->post_catalogue_id];
    }
    
    

    private function paginateSelect(){
        return [
            'posts.id', 
            'posts.publish',
            'posts.image',
            'posts.order',
            'tb2.name', 
            'tb2.canonical',
        ];
    }

    private function payload(){
        return [
            'follow',
            'publish',
            'image',
            'album',
            'post_catalogue_id',
            'video',
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
