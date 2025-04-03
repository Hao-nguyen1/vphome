<?php  
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class AgencyComposer
{


    public function __construct(
       
    ){
       
    }

    public function compose(View $view)
    {

        $agency = Auth::guard('agency')->user();
        $view->with('agencyAuth', $agency);
    }

   

}