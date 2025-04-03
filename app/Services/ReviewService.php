<?php

namespace App\Services;

use App\Services\Interfaces\ReviewServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\ReviewRepositoryInterface as ReviewRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Classes\ReviewNested;
use Illuminate\Support\Facades\Auth;

/**
 * Class AttributeService
 * @package App\Services
 */
class ReviewService extends BaseService implements ReviewServiceInterface
{
    protected $reviewRepository;
    
    public function __construct(
        ReviewRepository $reviewRepository,
    ){
        $this->reviewRepository = $reviewRepository;
    }


    public function paginate($request){
        $condition['keyword'] = addslashes($request->input('keyword'));

        $perPage = $request->integer('perpage');
        $reviews = $this->reviewRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'review/index'], 
        );

        return $reviews;
    }

    public function create($request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token');
            // dd($payload);
            $review = $this->reviewRepository->create($payload);
            $this->reviewNestedset = new ReviewNested([
                'table' => 'reviews',
                'reviewable_type' => $payload['reviewable_type']
            ]);
            $this->reviewNestedset->Get('level ASC, order ASC');
            $this->reviewNestedset->Recursive(0, $this->reviewNestedset->Set());
            $this->reviewNestedset->Action();
            DB::commit();
            return [
                'code' => 10,
                'message' => 'Đánh giá sản phẩm thành công'
            ];
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return [
                'code' => 11,
                'message' => 'Có vấn đề xảy ra! Hãy thử lại'
            ];
        }
    }

    private function paginateSelect(){
        return [
            'id', 
            'reviewable_id', 
            'reviewable_type',
            'email', 
            'phone',
            'fullname',
            'gender',
            'score',
            'description',
            'created_at',
        ];

    }

}
