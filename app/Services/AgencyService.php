<?php

namespace App\Services;
use App\Services\Interfaces\AgencyServiceInterface;
use App\Repositories\Interfaces\AgencyRepositoryInterface as AgencyRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 * @package App\Services
 */
class AgencyService extends BaseService implements AgencyServiceInterface 
{
    protected $accountRepository;

    public function __construct(
        AgencyRepository $agencyRepository
    ){
        $this->agencyRepository = $agencyRepository;
    }

    public function paginate($request){
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish')
        ];
        $perPage = $request->integer('perpage');
        $agencys = $this->agencyRepository->agencyPagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage, 
            ['path' => 'agency/index'], 
        );
        return $agencys;
    }

    public function create($request){
        DB::beginTransaction();
        try{
            $payload = $request->except(['_token','send','re_password']);
            $payload['password'] = Hash::make($payload['password']);
            $agency = $this->agencyRepository->create($payload);
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
            $agency = $this->agencyRepository->update($id, $payload);
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
            $agency = $this->agencyRepository->delete($id);
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
            'email',
            'code',
            'phone',
            'publish',
            'address',
        ];
    }
}
