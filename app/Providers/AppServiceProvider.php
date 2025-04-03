<?php

namespace App\Providers;

use App\Http\ViewComposers\AgencyComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Carbon\Carbon;
use App\Http\ViewComposers\SystemComposer;
use App\Http\ViewComposers\MenuComposer;
use App\Http\ViewComposers\LanguageComposer;
use App\Http\ViewComposers\CategoryComposer;
use App\Http\ViewComposers\CartComposer;
use App\Http\ViewComposers\WishlistComposer;
use App\Http\ViewComposers\CustomerComposer;
use App\Http\ViewComposers\ProductCatalogueComposer;
use App\Models\Language;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{

    public $bindings = [
        'App\Services\Interfaces\UserServiceInterface' => 'App\Services\UserService',
        'App\Services\Interfaces\UserCatalogueServiceInterface' => 'App\Services\UserCatalogueService',
        'App\Services\Interfaces\CustomerServiceInterface' => 'App\Services\CustomerService',
        'App\Services\Interfaces\CustomerCatalogueServiceInterface' => 'App\Services\CustomerCatalogueService',
        'App\Services\Interfaces\LanguageServiceInterface' => 'App\Services\LanguageService',
        'App\Services\Interfaces\PostCatalogueServiceInterface' => 'App\Services\PostCatalogueService',
        'App\Services\Interfaces\GenerateServiceInterface' => 'App\Services\GenerateService',
        'App\Services\Interfaces\PermissionServiceInterface' => 'App\Services\PermissionService',
        'App\Services\Interfaces\PostServiceInterface' => 'App\Services\PostService',
        'App\Services\Interfaces\ProductCatalogueServiceInterface' => 'App\Services\ProductCatalogueService',
        'App\Services\Interfaces\ProductServiceInterface' => 'App\Services\ProductService',
        'App\Services\Interfaces\ProductCatalogueServiceInterface' => 'App\Services\ProductCatalogueService',
        'App\Services\Interfaces\ProductServiceInterface' => 'App\Services\ProductService',
        'App\Services\Interfaces\AttributeCatalogueServiceInterface' => 'App\Services\AttributeCatalogueService',
        'App\Services\Interfaces\AttributeServiceInterface' => 'App\Services\AttributeService',
        'App\Services\Interfaces\SystemServiceInterface' => 'App\Services\SystemService',
        'App\Services\Interfaces\MenuCatalogueServiceInterface' => 'App\Services\MenuCatalogueService',
        'App\Services\Interfaces\MenuServiceInterface' => 'App\Services\MenuService',
        'App\Services\Interfaces\SlideServiceInterface' => 'App\Services\SlideService',
        'App\Services\Interfaces\WidgetServiceInterface' => 'App\Services\WidgetService',
        'App\Services\Interfaces\PromotionServiceInterface' => 'App\Services\PromotionService',
        'App\Services\Interfaces\SourceServiceInterface' => 'App\Services\SourceService',
        'App\Services\Interfaces\CartServiceInterface' => 'App\Services\CartService',
        'App\Services\Interfaces\OrderServiceInterface' => 'App\Services\OrderService',
        'App\Services\Interfaces\ReviewServiceInterface' => 'App\Services\ReviewService',
        'App\Services\Interfaces\DistributionServiceInterface' => 'App\Services\DistributionService',
        'App\Services\Interfaces\AgencyServiceInterface' => 'App\Services\AgencyService',
        'App\Services\Interfaces\ConstructServiceInterface' => 'App\Services\ConstructService',
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach($this->bindings as $key => $val)
        {
            $this->app->bind($key, $val);
        }

        $this->app->register(RepositoryServiceProvider::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        $locale = app()->getLocale(); // vn en cn
        $language = Language::where('canonical', $locale)->first();

        Validator::extend('custom_date_format', function($attribute, $value, $parameters, $validator){
            return Datetime::createFromFormat('d/m/Y H:i', $value) !== false;
        });

        Validator::extend('custom_after', function($attribute, $value, $parameters, $validator){
            $startDate = Carbon::createFromFormat('d/m/Y H:i', $validator->getData()[$parameters[0]]);
            $endDate = Carbon::createFromFormat('d/m/Y H:i', $value);
            
            return $endDate->greaterThan($startDate) !== false;
        });


        view()->composer('*', function($view) use ($language){
            $composerClasses = [
                // SystemComposer::class,
                MenuComposer::class,
                LanguageComposer::class,
                CategoryComposer::class,
                CartComposer::class,
                WishlistComposer::class,
                CustomerComposer::class,
                AgencyComposer::class,
                ProductCatalogueComposer::class,
            ];

            foreach($composerClasses as $key => $val){
                $composer = app()->make($val, ['language' => $language->id]);
                $composer->compose($view);
            }
        });

      

     

        Schema::defaultStringLength(191);
    }
}
