<?php  
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Services\CartService;
use Cart;

class CartComposer
{

    protected $cartService;

    public function __construct(
        CartService $cartService,
    ){
       $this->cartService = $cartService;
    }

    public function compose(View $view)
    {
        
        $carts = Cart::instance('shopping')->content();
        $carts = $this->cartService->remakeCart($carts);
        $cartCaculate = $this->cartService->reCaculateCart();
        $view->with('cartShare', $cartCaculate);
    }

   

}