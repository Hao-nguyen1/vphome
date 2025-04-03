<?php  
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class CustomerComposer
{


    public function __construct(
       
    ){
       
    }

    public function compose(View $view)
    {
        $customer = Auth::guard('customer')->user();
        $view->with('customerAuth', $customer);
    }

   

}