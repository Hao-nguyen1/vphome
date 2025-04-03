<?php

namespace App\Services;

use App\Services\Interfaces\SourceServiceInterface;
use App\Repositories\Interfaces\SourceRepositoryInterface as SourceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class SourceService
 * @package App\Services
 */
class SourceService extends BaseService implements SourceServiceInterface 
{
    protected $sourceRepository;
    

    public function __construct(
        SourceRepository $sourceRepository
    ){
        $this->sourceRepository = $sourceRepository;
    }

    

    public function paginate($request){
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perPage = $request->integer('perpage');
        $sources = $this->sourceRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'source/index'], 
        );
        
        return $sources;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $payload = $request->only('name', 'keyword', 'description');
            $source = $this->sourceRepository->create($payload);
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
            $payload = $request->only('name', 'keyword', 'description');
            $source = $this->sourceRepository->update($id, $payload);
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
            $source = $this->sourceRepository->delete($id);

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
            'publish',
            'description',
        ];
    }
}
