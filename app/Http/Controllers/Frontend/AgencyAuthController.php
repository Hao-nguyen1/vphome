<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\FrontendController;
use App\Services\Interfaces\CustomerServiceInterface  as CustomerService;
use App\Services\Interfaces\AgencyServiceInterface  as AgencyService;
use App\Http\Requests\AuthRegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetAgencyPasswordMail; 
use App\Models\Customer;
use App\Models\Agency;

class AgencyAuthController extends FrontendController
{
    protected $customerService;
    protected $agencyService;
    public function __construct(
        CustomerService $customerService,
        AgencyService $agencyService,
    ){
        $this->customerService = $customerService;
        $this->agencyService = $agencyService;
        parent::__construct();
    }

    public function indexAgency(){
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang đăng nhập - Hệ thống website '.$this->system['homepage_company'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('fe.auth.agency.login')
        ];
        return view('frontend.auth.indexAgency', compact(
            'seo',
            'system',
        ));
    }

    
    public function forgotAgencyPassword(){
        $seo = [
            'meta_title' => 'Thông tin kích hoạt bảo hành',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('forgot.agency.password')
        ];
        $system = $this->system;
        $route = 'agency.password.email';
        return view('frontend.auth.components.forgotPassword',compact(
            'seo',
            'system',
            'route'
        ));
    }

    public function verifyAgencyEmail(Request $request){
        $emailReset = $request->input('email');
        $agency = Agency::where('email', $emailReset)->first();
        if(!is_null($agency)){
            Mail::to($emailReset)->send(new ResetAgencyPasswordMail($emailReset));
            return redirect()->route('fe.auth.agency.login')
            ->with('success','Gửi yêu cầu cập nhật mật khẩu thành công, vui lòng truy cập email của bạn để cập nhật mật khẩu mới');
        }
        return redirect()->route('forgot.agency.password')->with('error','Gửi yêu cầu cập nhật mật khẩu không thành công, email không tồn tại trong hệ thống');
    }


    public function updatePassword(Request $request){
        $email = rtrim(urldecode($request->getQueryString('email')), '=');
        $seo = [
            'meta_title' => 'Thông tin kích hoạt bảo hành',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('agency.password.reset')
        ];
        $system = $this->system;
        $route = 'agency.password.reset';
        return view('frontend.auth.components.updatePassword',compact(
            'system',
            'seo',
            'route',
            'email'
        ));
    }
    
    public function changePassword(Request $request)
    {
        $email = base64_decode(rtrim(urldecode($request->getQueryString('email')), '='));
        $agency = Agency::where('email', $email)->first();

        if($this->agencyService->update($agency->id, $request)) {
            return redirect()->route('fe.auth.agency.login')->with('success', 'Cập nhật mật khẩu mới thành công');
        }
        return redirect()->route('agency.update.password')->with('error', 'Cập nhật mật khẩu không thành công. Hãy thử lại');
    }


    public function login(Request $request){
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
        if(Auth::guard('agency')->attempt($credentials)){
            $user = Auth::guard('agency')->user();
            $request->session()->regenerate();
            return redirect()->route('home.index')->with('success', 'Đăng nhập thành công');
        }
        return redirect()->route('home.index')->with('error','Email hoặc Mật khẩu không chính xác');
    }


}
