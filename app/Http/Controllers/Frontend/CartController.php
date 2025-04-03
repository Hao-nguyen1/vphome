<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface  as ProvinceRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface  as PromotionRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface  as OrderRepository;
use App\Http\Requests\StoreCartRequest;
use Cart;
use App\Classes\Vnpay;
use App\Classes\Momo;
use App\Classes\Paypal;
use App\Classes\Zalo;

class CartController extends FrontendController
{
  
    protected $provinceRepository;
    protected $promotionRepository;
    protected $orderRepository;
    protected $cartService;
    protected $vnpay;
    protected $momo;
    protected $paypal;
    protected $zalo;

    public function __construct(
        ProvinceRepository $provinceRepository,
        PromotionRepository $promotionRepository,
        OrderRepository $orderRepository,
        CartService $cartService,
        Vnpay $vnpay,
        Momo $momo,
        Paypal $paypal,
        Zalo $zalo,
    ){
       
        $this->provinceRepository = $provinceRepository;
        $this->promotionRepository = $promotionRepository;
        $this->orderRepository = $orderRepository;
        $this->cartService = $cartService;
        $this->vnpay = $vnpay;
        $this->momo = $momo;
        $this->paypal = $paypal;
        $this->zalo = $zalo;
        parent::__construct();
    }


    public function checkout(){
        $provinces = $this->provinceRepository->all();
        $carts = Cart::instance('shopping')->content();
        $carts = $this->cartService->remakeCart($carts);
        $cartCaculate = $this->cartService->reCaculateCart();
        $cartPromotion = $this->cartService->cartPromotion($cartCaculate['cartTotal']);

        $seo = [
            'meta_title' => 'Trang thanh toán đơn hàng',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('thanh-toan', TRUE, TRUE),
        ];
        $system = $this->system;
        $config = $this->config();
        return view('frontend.cart.index', compact(
            'config',
            'seo',
            'system',
            'provinces',
            'carts',
            'cartPromotion',
            'cartCaculate',
        ));
        
    }

    public function store(StoreCartRequest $request){
        $system = $this->system;
        $order = $this->cartService->order($request, $system);
        if($order['flag']){
            if($order['order']->method !== 'cod'){
                $response = $this->paymentMethod($order);
                if($response['errorCode'] == 0){
                    return redirect()->away($response['url']);
                }
            }
            return redirect()->route('cart.success', ['code' => $order['order']->code])->with('success','Đặt hàng thành công');
        }
        return redirect()->route('cart.checkout')->with('error','Đặt hàng không thành công. Hãy thử lại');
    }

    public function success($code){
        $order = $this->orderRepository->findByCondition([
            ['code', '=', $code],
        ], false, ['products']);
        
        $seo = [
            'meta_title' => 'Thanh toán đơn hàng thành công',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('cart/success', TRUE, TRUE),
        ];
        $system = $this->system;
        $config = $this->config();
        return view('frontend.cart.success', compact(
            'config',
            'seo',
            'system',
            'order'
        ));
    }

    public function paymentMethod($order = null){
        $class = $order['order']->method;
        $response = $this->{$class}->payment($order['order']);
        return $response;
    }

    
    private function config(){
        return [
            'language' => $this->language,
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'backend/library/location.js',
                'frontend/core/library/cart.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ]
        ];
    }
  

}
