<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Agency\EditProfileAgencyRequest;
use App\Http\Requests\Agency\RecoverAgencyPasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Services\Interfaces\AgencyServiceInterface  as AgencyService;
use App\Services\Interfaces\CustomerServiceInterface  as CustomerService;
use App\Services\Interfaces\ConstructServiceInterface  as ConstructService;
use App\Repositories\Interfaces\ConstructRepositoryInterface  as ConstructRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface  as CustomerRepository;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface  as CustomerCatalogueRepository;
use App\Repositories\Interfaces\SourceRepositoryInterface  as SourceRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface  as ProvinceRepository;
use App\Http\Requests\Construct\StoreConstructRequest;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Construct\UpdateConstructRequest;

class AgencyController extends FrontendController
{
    protected $agencyService;
    protected $customerService;
    protected $constructRepository;
    protected $customerRepository;
    protected $customerCatalogueRepository;
    protected $sourceRepository;
    protected $provinceRepository;
    protected $constructService;
    protected $customer;

    public function __construct(
        AgencyService $agencyService,
        CustomerService $customerService,
        ConstructRepository $constructRepository,
        CustomerRepository $customerRepository,
        CustomerCatalogueRepository $customerCatalogueRepository,
        SourceRepository $sourceRepository,
        ProvinceRepository $provinceRepository,
        ConstructService $constructService,

    ){
        $this->agencyService = $agencyService;
        $this->customerService = $customerService;
        $this->constructService = $constructService;
        $this->constructRepository = $constructRepository;
        $this->provinceRepository = $provinceRepository;
        $this->customerRepository = $customerRepository;
        $this->customerCatalogueRepository = $customerCatalogueRepository;
        $this->sourceRepository = $sourceRepository;

        parent::__construct();
    
    }

    public function profile(){
        $agency = Auth::guard('agency')->user();
    
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang quản lý tài khoản khách hàng'.$agency['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.agency.profile', compact(
            'seo',
            'system',
            'agency',
        ));
    }

    public function updateProfile(EditProfileAgencyRequest $request){
        $agencyId =  Auth::guard('agency')->user()->id;       
        if($this->agencyService->update($agencyId, $request)){
            return redirect()->route('agency.profile')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('agency.profile')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function passwordForgot(){
        $agency = Auth::guard('agency')->user();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang thay đổi mật khẩu'.$agency['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('agency.profile')
        ];
        return view('frontend.auth.agency.password', compact(
            'seo',
            'system',
            'agency',
        ));
    }

    public function recovery(RecoverAgencyPasswordRequest $request){
        $agency = Auth::guard('agency')->user();

        if (!Hash::check($request->password, $agency->password)) {
            return redirect()->back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }
        $agency->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('agency.profile')->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }

    public function construction(Request $request){
        $agency = Auth::guard('agency')->User();
        $condition = [
            'keyword' => $request->input('keyword'),
            'confirm' => $request->input('confirm')
        ];
        $constructs = $this->constructRepository->findConstructByAgency($agency->id, $condition);
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang quản lý danh sách công trình của '.$agency['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('agency.profile')
        ];
    
        return view('frontend.auth.agency.construction', compact(
            'seo',
            'system',
            'agency',
            'constructs',
        ));
    }

    public function customer(Request $request){
        $agency = Auth::guard('agency')->User();
        $condition = [
            'keyword' => $request->input('keyword'),
            'confirm' => $request->input('confirm')
        ];
        $constructs = $this->constructRepository->findCustomerByConstruct($agency->id);
        $customer_id = convertArray($constructs);
        $customers = $this->customerRepository->getCustomer($customer_id, $condition);
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang quản lý danh sách công trình của '.$agency['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('agency.profile')
        ];
        return view('frontend.auth.agency.customer', compact(
            'seo',
            'system',
            'agency',
            'customers',
        ));
    }

    public function createConstruction(){
        $agency = Auth::guard('agency')->User();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang quản lý danh sách công trình của '.$agency['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('agency.profile')
        ];
        $config = $this->configData();
        $config['method'] = 'create';
        $config['seo'] = __('messages.construct');
        $constructs = $this->constructRepository->findCustomerByConstruct($agency->id);
        $customer_id = convertArray($constructs);
        $customers = convertToIdNameArray($this->customerRepository->getCustomer($customer_id));
        $provinces =  $this->provinceRepository->all();
        return view('frontend.auth.agency.storeConstruction',compact(
            'customers',
            'provinces',
            'system',
            'agency',
            'seo',
            'config'
        ));
    }
    
    public function storeConstruction(StoreConstructRequest $request){
        if($this->constructService->create($request)){
            return redirect()->route('agency.profile')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('construction.create')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function editConstruction($id){
        $agency = Auth::guard('agency')->User();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang quản lý danh sách công trình của '.$agency['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('agency.profile')
        ];
        $config = $this->configData();
        $config['method'] = 'edit';
        $construct = $this->constructRepository->findById($id, ['*'], ['products' => function($query){
            $query->with(['languages']);
        }]);
        $provinces = $this->provinceRepository->all();
        $customer_id = convertArray($this->constructRepository->findCustomerByConstruct($agency->id));
        $customers = convertToIdNameArray($this->customerRepository->getCustomer($customer_id));
        $productConstruction = $this->constructService->productConstruction($construct->products);

        return view('frontend.auth.agency.storeConstruction',compact(
            'system',
            'agency',
            'seo',
            'config',
            'construct',
            'provinces',
            'productConstruction',
            'customers'
        ));
    }

    public function updateConstruction($id, UpdateConstructRequest $request){
        if($this->constructService->update($id,$request)){
            return redirect()->route('agency.construction')->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('agency.construction')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function deleteConstruction($id){
        $agency = Auth::guard('agency')->User();
        $seo = [
            'meta_title' => 'Trang quản lý danh sách công trình của '.$agency['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('agency.profile')
        ];
        $construct = $this->constructRepository->findById($id);
        $template = 'backend.construct.delete';
        $system = $this->system;
        return view('frontend.auth.agency.deleteConstruction', compact(
            'template',
            'construct',
            'system',
            'seo'
        ));
    }

    public function destroyConstruction($id){
        if($this->constructService->destroy($id)){
            return redirect()->route('agency.construction')->with('success','Xóa bản ghi thành công');
        }
        return redirect()->route('agency.construction')->with('error','Xóa bản ghi không thành công. Hãy thử lại');
    }

    public function createCustomer(){
        $agency = Auth::guard('agency')->User();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang quản lý danh sách công trình của '.$agency['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('agency.profile')
        ];
        $config = $this->configData();
        $customerCatalogues = $this->customerCatalogueRepository->all();
        $sources = $this->sourceRepository->all();
        $provinces =  $this->provinceRepository->all();
        return view('frontend.auth.agency.createCustomer',compact(
            'customerCatalogues',
            'provinces',
            'system',
            'sources',
            'seo',
            'config',
            'agency'
        ));
    }

    public function storeCustomer(StoreCustomerRequest $request){
        if($this->customerService->create($request)){
            return redirect()->route('agency.profile')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('customer.create')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function constructionProduct($id){
        $agency = Auth::guard('agency')->user();

        $construction = $this->constructRepository->findById($id, ['*'], ['products']);
        $system = $this->system;
        $seo = [
            'meta_title' => 'Chi tiết sản phẩm công trình '.$construction->name.' của '.$agency['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('agency.profile')
        ];
        return view('frontend.auth.agency.product', compact(
            'seo',
            'system',
            'agency',
            'construction',
        ));
    }

    public function warranty(Request $request){
        $agency = Auth::guard('agency')->user();
        

        $condition = [
            'keyword' => $request->input('keyword'),
            'confirm' => $request->input('status')
        ];

        $warranty = $this->constructRepository->warrantyAgency($agency->id, $condition);
         

        $system = $this->system;
        $seo = [
            'meta_title' => 'Thông tin kích hoạt bảo hành',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('agency.profile')
        ];
        return view('frontend.auth.agency.warranty', compact(
            'seo',
            'system',
            'agency',
            'warranty',
        ));
    }

    
    public function logout(){
        Auth::guard('agency')->logout();
        return redirect()->route('home.index')->with('success', 'Bạn đã đăng xuất khỏi hệ thống.');
    }

    private function configData(){
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/css/bootstrap.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/construct.js',
                'backend/library/location.js',
            ]
        ];
    }
    
}
