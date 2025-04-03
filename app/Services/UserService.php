<?php

namespace App\Services;

use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 * @package App\Services
 */
class UserService extends BaseService implements UserServiceInterface 
{
    protected $userRepository;
    

    public function __construct(
        UserRepository $userRepository
    ){
        $this->userRepository = $userRepository;
    }

    

    public function paginate($request){
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perPage = $request->integer('perpage');
        $users = $this->userRepository->userPagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'user/index'], 
        );
        
        // dd($users);

        
        return $users;
    }

    public function create($request){
        DB::beginTransaction();
        try{

            $payload = $request->except(['_token','send','re_password']);
            if($payload['birthday'] != null){
                $payload['birthday'] = $this->convertBirthdayDate($payload['birthday']);
            }
            $payload['password'] = Hash::make($payload['password']);
            $user = $this->userRepository->create($payload);
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
            if($payload['birthday'] != null){
                $payload['birthday'] = $this->convertBirthdayDate($payload['birthday']);
            }
            $user = $this->userRepository->update($id, $payload);
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
            $user = $this->userRepository->delete($id);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

   
    private function convertBirthdayDate($birthday = ''){
        $carbonDate = Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday = $carbonDate->format('Y-m-d H:i:s');
        return $birthday;
    }
    
    private function paginateSelect(){
        return [
            'id', 
            'email', 
            'phone',
            'address', 
            'name',
            'publish',
            'user_catalogue_id'
        ];
    }


}
