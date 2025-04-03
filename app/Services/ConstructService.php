<?php

namespace App\Services;
use App\Services\Interfaces\ConstructServiceInterface;
use App\Repositories\Interfaces\ConstructRepositoryInterface as ConstructRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 * @package App\Services
 */
class ConstructService extends BaseService implements ConstructServiceInterface 
{
    protected $accountRepository;

    public function __construct(
        ConstructRepository $constructRepository
    ){
        $this->constructRepository = $constructRepository;
    }

    public function paginate($request,$extend = []){
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish')
        ];
        $orderBy = ['id', 'desc'];
        $paginationConfig = [
            'path' => ($extend['path']) ?? 'construct/index', 
        ];
        $perPage = $request->integer('perpage');
        $constructs = $this->constructRepository->constructPagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage, 
            $paginationConfig,
            $orderBy,
            [],
            ['customers']
        );
        return $constructs;
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('agency_id', 'customer_id', 'province_id', 'invester', 'workshop', 'point', 'code', 'address', 'name', 'confirm');
            $payload['point'] = convert_price($payload['point']);
            $construction = $this->constructRepository->create($payload);
            if($construction->id > 0){
                $product = $this->handleProduct($request->only('product'));
                $construction->products()->attach($product);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }

    private function handleProduct($product){
        $temp = [];
        foreach($product['product']['name'] as $key => $val){
            $temp[] = [
                'product_id' => $product['product']['id'][$key],
                'warranty' => convert_price($product['product']['warranty'][$key]),
                'quantity' => $product['product']['quantity'][$key],
                'color' => $product['product']['color'][$key],
                'status' => 'pending',
            ];
        }
        return $temp;
    }

    public function update($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->only('agency_id', 'customer_id', 'province_id', 'invester', 'workshop', 'point', 'code', 'address', 'name', 'confirm');
            $payload['point'] = convert_price($payload['point']);

            $construction = $this->constructRepository->findById($id);
            if($this->constructRepository->update($id, $payload)){
                $product = $this->handleProduct($request->only('product'));
                $construction->products()->detach();
                $construction->products()->attach($product);
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
            $construct = $this->constructRepository->delete($id);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }


    public function productConstruction($data){
        $array = convertArrayByKey($data, ['id', 'name.languages', 'color.products', 'warranty.products', 'quantity.products']);
        return $array;
    }


    public function activeWarranty($request, $status){

        DB::beginTransaction();
        try{
            $payload = $request->only('construct_id', 'product_id', 'warranty');
            $payload['startDate'] = now();
            $payload['endDate'] = now()->addMonths($payload['warranty']);
            $construction = $this->constructRepository->findById($payload['construct_id']);
          
            $construction->products()->updateExistingPivot($payload['product_id'], ['status' => $status, 'startDate' => $payload['startDate'], 'endDate' => $payload['endDate']]);


            DB::commit();
            return [
                'flag' => true,
                'startDate' => $payload['startDate'],
                'endDate' => $payload['endDate']
            ];
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
      
        
    }

    public function paginateWarranty($request,$extend = []){
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'status' => $request->input('status')
        ];
        $orderBy = ['constructions.id', 'desc'];
        $joins = [
            ['construction_product as tb2', 'constructions.id', '=', 'tb2.construction_id'],
            ['product_language as tb4', 'tb4.product_id', '=', 'tb2.product_id'],
        ];
        $paginationConfig = [
            'path' => ($extend['path']) ?? 'construct/index', 
        ];
        $perPage = $request->integer('perpage');
        $constructs = $this->constructRepository->getWarrantyRequest(
            $this->paginateSelectWarranty(),
            $condition, 
            $perPage, 
            $paginationConfig,
            $orderBy,
            $joins,
            ['customers','products']
        );
        return $constructs;
    }

    private function paginateSelectWarranty(){
        return [
            'constructions.id',
            'constructions.name',
            'constructions.code',
            'constructions.customer_id',
            'constructions.agency_id',
            'constructions.invester',
            'tb2.product_id',
            'tb2.status',
            'tb2.startDate',
            'tb2.endDate',
            'tb4.name as product_name',
            // DB::raw('GROUP_CONCAT(tb4.name) as product_name'),
        ];
    }
    
    private function paginateSelect(){
        return [
            'id',
            'name',
            'code',
            'province_id',
            'point',
            'invester',
            'workshop',
            'customer_id',
            'agency_id',
            'address',
            'publish',
            'confirm',
        ];
    }

}
