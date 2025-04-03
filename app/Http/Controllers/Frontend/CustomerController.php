<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Customer\EditProfileRequest;
use App\Http\Requests\Customer\RecoverCustomerPasswordRequest;
use App\Services\Interfaces\CustomerServiceInterface  as CustomerService;
use App\Services\Interfaces\ConstructServiceInterface  as ConstructService;
use App\Repositories\Interfaces\ConstructRepositoryInterface  as ConstructRepository;

class CustomerController extends FrontendController
{
  
    protected $customerService;
    protected $constructRepository;
    protected $constructService;
    protected $customer;

    public function __construct(
        CustomerService $customerService,
        ConstructRepository $constructRepository,
        ConstructService $constructService,

    ){

        $this->customerService = $customerService;
        $this->constructService = $constructService;
        $this->constructRepository = $constructRepository;

        parent::__construct();
    
    }

  
    public function profile(){

        $customer = Auth::guard('customer')->user();
       
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang quản lý tài khoản khách hàng'.$customer['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.customer.profile', compact(
            'seo',
            'system',
            'customer',
        ));
    }

    public function updateProfile(EditProfileRequest $request){
        $customerId =  Auth::guard('customer')->user()->id;       
        if($this->customerService->update($customerId, $request)){
            return redirect()->route('customer.profile')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('customer.profile')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function passwordForgot(){

        $customer = Auth::guard('customer')->user();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang thay đổi mật khẩu'.$customer['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.customer.password', compact(
            'seo',
            'system',
            'customer',
        ));
    }

    public function recovery(RecoverCustomerPasswordRequest $request){
        $customer = Auth::guard('customer')->user();

        if (!Hash::check($request->password, $customer->password)) {
            return redirect()->back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }
        // Thay đổi mật khẩu
        $customer->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('customer.profile')->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }

    public function logout(){
        Auth::guard('customer')->logout();
        return redirect()->route('home.index')->with('success', 'Bạn đã đăng xuất khỏi hệ thống.');
    }

    public function construction(Request $request){
        $customer = Auth::guard('customer')->user();
        $condition = [
            'keyword' => $request->input('keyword'),
            'confirm' => $request->input('confirm')
        ];
        $constructs = $this->constructRepository->findConstructByCustomer($customer->id, $condition);
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang quản lý danh sách công trình của '.$customer['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
    
        return view('frontend.auth.customer.construction', compact(
            'seo',
            'system',
            'customer',
            'constructs',
        ));
    }
    

    public function constructionProduct($id){
        $customer = Auth::guard('customer')->user();

        $construction = $this->constructRepository->findById($id, ['*'], ['products']);

        $system = $this->system;
        $seo = [
            'meta_title' => 'Chi tiết sản phẩm công trình '.$construction->name.' của '.$customer['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.customer.product', compact(
            'seo',
            'system',
            'customer',
            'construction',
        ));
    }

    public function warranty(Request $request){
        $customer = Auth::guard('customer')->user();

        $condition = [
            'keyword' => $request->input('keyword'),
            'confirm' => $request->input('status')
        ];

        $warranty = $this->constructRepository->warranty($customer->id, $condition);


        $system = $this->system;
        $seo = [
            'meta_title' => 'Thông tin kích hoạt bảo hành',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.customer.warranty', compact(
            'seo',
            'system',
            'customer',
            'warranty',
        ));
    }

    
    public function active(Request $request){
        $response = $this->constructService->activeWarranty($request, 'active');
        return response()->json($response); 

    }
  

}
