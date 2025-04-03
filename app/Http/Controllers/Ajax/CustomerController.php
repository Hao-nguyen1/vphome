<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CustomerRepositoryInterface  as CustomerRepository;
use App\Services\Interfaces\CustomerServiceInterface  as CustomerService;
use App\Http\Requests\Customer\StoreCustomerRequest;


class CustomerController extends Controller
{

    protected $customerService;
    
    public function __construct(
        CustomerService $customerService,
    ){
        $this->customerService = $customerService;
    }

    public function createCustomer(StoreCustomerRequest $request){
        $customer = $this->customerService->create($request); 
        if($customer !== FALSE){
            return response()->json([
                'code' => 0,
                'message' => 'Tạo khách hàng thành công!',
                'data' => $customer,
            ]);
        }
        return response()->json([
            'message' => 'Có vấn đề xảy ra, hãy thử lại',
            'code' => 1
        ]);
    }

}

