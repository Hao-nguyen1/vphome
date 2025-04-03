<?php

namespace App\Services;

use App\Services\Interfaces\DistributionServiceInterface;
use App\Repositories\Interfaces\DistributionRepositoryInterface as DistributionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class DistributionService
 * @package App\Services
 */
class DistributionService extends BaseService implements DistributionServiceInterface 
{
    protected $distributionRepository;
    

    public function __construct(
        DistributionRepository $distributionRepository
    ){
        $this->distributionRepository = $distributionRepository;
    }

    

    public function paginate($request){
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perPage = $request->integer('perpage');
        $distributions = $this->distributionRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'distribution/index'], 
        );
        
        return $distributions;
    }

    private function request($request){
        return $request->except(['_token', 'send']);
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $payload = $this->request($request);
            $distribution = $this->distributionRepository->create($payload);
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
            $payload = $this->request($request);
            $distribution = $this->distributionRepository->update($id, $payload);
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
            $distribution = $this->distributionRepository->delete($id);

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
            'phone',
            'email',
            'address',
            'publish',
            'map',
        ];
    }
}
