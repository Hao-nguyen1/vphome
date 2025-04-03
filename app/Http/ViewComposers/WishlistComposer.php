<?php  
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Services\CartService;
use Cart;

class WishlistComposer
{

    protected $cartService;

    public function __construct(
        CartService $cartService,
    ){
       $this->cartService = $cartService;
    }

    public function compose(View $view)
    {
        
        $wishlist = Cart::instance('wishlist')->content();
        $view->with('wishlistShare', $wishlist);
    }

   

}