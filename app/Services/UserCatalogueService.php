<?php

namespace App\Services;

use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserCatalogueService
 * @package App\Services
 */
class UserCatalogueService extends BaseService implements UserCatalogueServiceInterface
{
    protected $userCatalogueRepository;
    protected $userRepository;
    

    public function __construct(
        UserCatalogueRepository $userCatalogueRepository,
        UserRepository $userRepository
    ){
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->userRepository = $userRepository;
    }

    

    public function paginate($request){
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish')
        ];
        $perPage = $request->integer('perpage');
        $userCatalogues = $this->userCatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage, 
            ['path' => 'user/catalogue/index'], 
            ['id', 'DESC'],  
            [],
            ['users']
        );
        return $userCatalogues;
    }

    public function create($request){
        DB::beginTransaction();
        try{
            $payload = $request->except(['_token','send']);
            $user = $this->userCatalogueRepository->create($payload);
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
            $user = $this->userCatalogueRepository->update($id, $payload);
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
            $user = $this->userCatalogueRepository->delete($id);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }


    private function changeUserStatus($post, $value){
        DB::beginTransaction();
        try{
            $array = [];
            if(isset($post['modelId'])){
                $array[] = $post['modelId'];
            }else{
                $array = $post['id'];
            }
            $payload[$post['field']] = $value;
            $this->userRepository->updateByWhereIn('user_catalogue_id', $array, $payload);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    public function setPermission($request){
        DB::beginTransaction();
        try{
           
            $permissions = $request->input('permission');
            if(count($permissions)){
                foreach($permissions as $key => $val){
                    $userCatalogue = $this->userCatalogueRepository->findById($key);
                    $userCatalogue->permissions()->detach();
                    $userCatalogue->permissions()->sync($val);
                   
                }
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
        //Mục đích là đưa được dữ liệu vào bên trong bảng user_catalogue_permission
    }


    private function paginateSelect(){
        return [
            'id', 
            'name', 
            'description',
            'publish',

        ];
    }


}
