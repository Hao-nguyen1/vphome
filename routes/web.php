<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Controllers\Backend\User\AuthController;
use App\Http\Controllers\Backend\DashboardController;

use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Backend\User\UserController;
use App\Http\Controllers\Backend\User\UserCatalogueController;
use App\Http\Controllers\Backend\User\PermissionController;
use App\Http\Controllers\Backend\Customer\CustomerController;
use App\Http\Controllers\Backend\Customer\CustomerCatalogueController;
use App\Http\Controllers\Backend\Customer\SourceController;
use App\Http\Controllers\Backend\DistributionController;
use App\Http\Controllers\Backend\Post\PostCatalogueController;
use App\Http\Controllers\Backend\Post\PostController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\GenerateController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\SlideController;
use App\Http\Controllers\Backend\WidgetController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\Promotion\PromotionController;
use App\Http\Controllers\Backend\ReviewController;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\AttributeController as AjaxAttributeController;
use App\Http\Controllers\Ajax\MenuController as AjaxMenuController;
use App\Http\Controllers\Ajax\SlideController as AjaxSlideController;
use App\Http\Controllers\Ajax\ProductController as AjaxProductController;
use App\Http\Controllers\Ajax\SourceController as AjaxSourceController;
use App\Http\Controllers\Ajax\CartController as AjaxCartController;
use App\Http\Controllers\Ajax\OrderController as AjaxOrderController;
use App\Http\Controllers\Ajax\ReviewController as AjaxReviewController;
use App\Http\Controllers\Ajax\PostController as AjaxPostController;
use App\Http\Controllers\Ajax\DistributionController as AjaxDistributionController;
use App\Http\Controllers\Backend\Product\ProductCatalogueController;
use App\Http\Controllers\Backend\Product\ProductController;
use App\Http\Controllers\Backend\Attribute\AttributeCatalogueController;
use App\Http\Controllers\Backend\Attribute\AttributeController;
use App\Http\Controllers\Backend\SystemController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\RouterController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\Payment\VnpayController;
use App\Http\Controllers\Frontend\Payment\MomoController;
use App\Http\Controllers\Frontend\Payment\PaypalController;
use App\Http\Controllers\Frontend\CrawlerController;
use App\Http\Controllers\Frontend\ContactController as FeContactController;
use App\Http\Controllers\Frontend\AuthController as FeAuthController;
use App\Http\Controllers\Frontend\AgencyAuthController as FeAgencyAuthController;
use App\Http\Controllers\Frontend\CustomerController as FeCustomerController;
use App\Http\Controllers\Frontend\AgencyController as FeAgencyController;

use App\Http\Controllers\Frontend\DistributionController as FeDistributionController;
use App\Http\Controllers\Frontend\ProductCatalogueController as FeProductCatalogueController;

use App\Http\Controllers\Backend\Crm\AgencyController;
use App\Http\Controllers\Backend\Crm\ConstructionController;
use App\Http\Controllers\Ajax\ConstructController as AjaxConstructController;
use App\Http\Controllers\Ajax\CustomerController as AjaxCustomerController;



//@@useController@@

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/* FRONTEND ROUTES  */
Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('crawler', [CrawlerController::class, 'index'])->name('crawler.ckfinder');
Route::get('crawlerUpdate', [CrawlerController::class, 'crawlerUpdate'])->name('crawler.update');
Route::get('crawlerProduct', [CrawlerController::class, 'crawlerProduct'])->name('crawler.product');
Route::get('crawlerUpdateProduct', [CrawlerController::class, 'updateProduct'])->name('crawler.product.update');


Route::get('tim-kiem'.config('apps.general.suffix'), [FeProductCatalogueController::class, 'search'])->name('product.catalogue.search');
Route::get('lien-he'.config('apps.general.suffix'), [FeContactController::class, 'index'])->name('fe.contact.index');


/* CUSTOMER  */
Route::get('customer/login'.config('apps.general.suffix'), [FeAuthController::class, 'index'])->name('fe.auth.login'); 
Route::get('customer/check/login'.config('apps.general.suffix'), [FeAuthController::class, 'login'])->name('fe.auth.dologin');

Route::get('customer/password/forgot'.config('apps.general.suffix'), [FeAuthController::class, 'forgotCustomerPassword'])->name('forgot.customer.password');
Route::get('customer/password/email'.config('apps.general.suffix'), [FeAuthController::class, 'verifyCustomerEmail'])->name('customer.password.email');
Route::get('customer/register'.config('apps.general.suffix'), [FeAuthController::class, 'register'])->name('customer.register');
Route::post('customer/reg'.config('apps.general.suffix'), [FeAuthController::class, 'registerAccount'])->name('customer.reg');


Route::get('customer/password/update'.config('apps.general.suffix'), [FeAuthController::class, 'updatePassword'])->name('customer.update.password');
Route::post('customer/password/change'.config('apps.general.suffix'), [FeAuthController::class, 'changePassword'])->name('customer.password.reset');


Route::group(['middleware' => ['customer']], function () {
   Route::get('customer/profile'.config('apps.general.suffix'), [FeCustomerController::class, 'profile'])->name('customer.profile');
   Route::post('customer/profile/update'.config('apps.general.suffix'), [FeCustomerController::class, 'updateProfile'])->name('customer.profile.update');
   Route::get('customer/password/reset'.config('apps.general.suffix'), [FeCustomerController::class, 'passwordForgot'])->name('customer.password.change');
   Route::post('customer/password/recovery'.config('apps.general.suffix'), [FeCustomerController::class, 'recovery'])->name('customer.password.recovery');
   Route::get('customer/logout'.config('apps.general.suffix'), [FeCustomerController::class, 'logout'])->name('customer.logout');
   Route::get('customer/construction'.config('apps.general.suffix'), [FeCustomerController::class, 'construction'])->name('customer.construction');
   Route::get('customer/construction/{id}/product'.config('apps.general.suffix'), [FeCustomerController::class, 'constructionProduct'])->name('customer.construction.product')->where(['id' => '[0-9]+']);
   Route::get('customer/warranty/check'.config('apps.general.suffix'), [FeCustomerController::class, 'warranty'])->name('customer.check.warranty');
   Route::post('customer/warranty/active', [FeCustomerController::class, 'active'])->name('customer.active.warranty');
});



/* AGENCY  */
Route::get('agency/login'.config('apps.general.suffix'), [FeAgencyAuthController::class, 'indexAgency'])->name('fe.auth.agency.login'); 
Route::get('agency/check/login'.config('apps.general.suffix'), [FeAgencyAuthController::class, 'login'])->name('fe.auth.agency.dologin');
Route::get('agency/password/forgot'.config('apps.general.suffix'), [FeAgencyAuthController::class,'forgotAgencyPassword'])->name('forgot.agency.password');
Route::get('agency/password/email'.config('apps.general.suffix'), [FeAgencyAuthController::class,'verifyAgencyEmail'])->name('agency.password.email');
Route::get('agency/password/update'.config('apps.general.suffix'), [FeAgencyAuthController::class, 'updatePassword'])->name('agency.update.password');
Route::post('agency/password/change'.config('apps.general.suffix'), [FeAgencyAuthController::class, 'changePassword'])->name('agency.password.reset');

Route::group(['middleware' => ['agency']], function () {
   Route::get('agency/profile'.config('apps.general.suffix'), [FeAgencyController::class, 'profile'])->name('agency.profile');
   Route::post('agency/profile/update'.config('apps.general.suffix'), [FeAgencyController::class, 'updateProfile'])->name('agency.profile.update');
   Route::get('agency/password/reset'.config('apps.general.suffix'), [FeAgencyController::class, 'passwordForgot'])->name('agency.password.change');
   Route::post('agency/password/recovery'.config('apps.general.suffix'), [FeAgencyController::class, 'recovery'])->name('agency.password.recovery');
   Route::get('agency/logout'.config('apps.general.suffix'), [FeAgencyController::class, 'logout'])->name('agency.logout');
   Route::get('agency/customer'.config('apps.general.suffix'), [FeAgencyController::class, 'customer'])->name('agency.customer');
   Route::get('agency/construction'.config('apps.general.suffix'), [FeAgencyController::class, 'construction'])->name('agency.construction');
   Route::get('agency/construction/{id}/product'.config('apps.general.suffix'), [FeAgencyController::class, 'constructionProduct'])->name('agency.construction.product')->where(['id' => '[0-9]+']);
   Route::get('agency/warranty/check'.config('apps.general.suffix'), [FeAgencyController::class, 'warranty'])->name('agency.check.warranty');
   Route::post('agency/warranty/active', [FeAgencyController::class, 'active'])->name('agency.active.warranty');

   Route::get('agency/construction/create', [FeAgencyController::class, 'createConstruction'])->name('agency.construction.create');
   Route::post('agency/construction/store', [FeAgencyController::class, 'storeConstruction'])->name('agency.construction.store');
   Route::get('agency/construction/{id}/edit', [FeAgencyController::class, 'editConstruction'])->where(['id' => '[0-9]+'])->name('agency.construction.edit');
   Route::post('agency/construction/{id}/update', [FeAgencyController::class, 'updateConstruction'])->where(['id' => '[0-9]+'])->name('agency.construction.update');
   Route::get('agency/construction/{id}/delete', [FeAgencyController::class, 'deleteConstruction'])->where(['id' => '[0-9]+'])->name('agency.construction.delete');
   Route::delete('agency/construction/{id}/destroy', [FeAgencyController::class, 'destroyConstruction'])->where(['id' => '[0-9]+'])->name('agency.construction.destroy');
   
   Route::get('agency/customer/create', [FeAgencyController::class, 'createCustomer'])->name('agency.customer.create');
   Route::post('agency/customer/store', [FeAgencyController::class, 'storeCustomer'])->name('agency.customer.store');
});




Route::get('he-thong-phan-phoi'.config('apps.general.suffix'), [FeDistributionController::class, 'index'])->name('distribution.list.index');
Route::get('danh-sach-yeu-thich'.config('apps.general.suffix'), [FeProductCatalogueController::class, 'wishlist'])->name('product.catalogue.wishlist');
Route::get('thanh-toan'.config('apps.general.suffix'), [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('{canonical}'.config('apps.general.suffix'), [RouterController::class, 'index'])->name('router.index')->where('canonical', '[a-zA-Z0-9-]+');
Route::get('{canonical}/trang-{page}'.config('apps.general.suffix'), [RouterController::class, 'page'])->name('router.page')->where('canonical', '[a-zA-Z0-9-]+')->where('page', '[0-9]+');
Route::post('cart/create', [CartController::class, 'store'])->name('cart.store');
Route::get('cart/{code}/success'.config('apps.general.suffix'), [CartController::class, 'success'])->name('cart.success')->where(['code' => '[0-9]+']);

/* FRONTEND SYSTEM */


Route::get('ajax/post/video', [AjaxPostController::class, 'video'])->name('post.video');
Route::post('ajax/product/wishlist', [AjaxProductController::class, 'wishlist'])->name('product.wishlist');

Route::get('ajax/distribution/getMap', [AjaxDistributionController::class, 'getMap'])->name('distribution.getMap');

/* VNPAY */
Route::get('return/vnpay'.config('apps.general.suffix'), [VnpayController::class, 'vnpay_return'])->name('vnpay.momo_return');
Route::get('return/vnpay_ipn'.config('apps.general.suffix'), [VnpayController::class, 'vnpay_ipn'])->name('vnpay.vnpay_ipn');

Route::get('return/momo'.config('apps.general.suffix'), [MomoController::class, 'momo_return'])->name('momo.momo_return');
Route::get('return/ipn'.config('apps.general.suffix'), [MomoController::class, 'vnpay_ipn'])->name('momo.momo_ipn');

Route::get('paypal/success'.config('apps.general.suffix'), [PaypalController::class, 'success'])->name('paypal.success');
Route::get('paypal/cancel'.config('apps.general.suffix'), [PaypalController::class, 'cancel'])->name('paypal.cancel');


/* FRONTEND AJAX ROUTE */
Route::post('ajax/review/create', [AjaxReviewController::class, 'create'])->name('ajax.review.create');
Route::get('ajax/product/loadVariant', [AjaxProductController::class, 'loadVariant'])->name('ajax.loadVariant');
Route::get('ajax/product/filter', [AjaxProductController::class, 'filter'])->name('ajax.filter');
Route::post('ajax/cart/create', [AjaxCartController::class, 'create'])->name('ajax.cart.create');
Route::post('ajax/cart/update', [AjaxCartController::class, 'update'])->name('ajax.cart.update');
Route::post('ajax/cart/delete', [AjaxCartController::class, 'delete'])->name('ajax.cart.delete');
Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location.index');
Route::post('updatePermission', [CustomerCatalogueController::class, 'updatePermission'])->name('customer.catalogue.updatePermission');


Route::get('ajax/dashboard/findModelObject', [AjaxDashboardController::class, 'findModelObject'])->name('ajax.dashboard.findModelObject');
/* BACKEND ROUTES */


Route::group(['middleware' => ['admin','locale','backend_default_locale']], function () {
   Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');

   /* USER */
   Route::group(['prefix' => 'user'], function () {
      Route::get('index', [UserController::class, 'index'])->name('user.index');
      Route::get('create', [UserController::class, 'create'])->name('user.create');
      Route::post('store', [UserController::class, 'store'])->name('user.store');
      Route::get('{id}/edit', [UserController::class, 'edit'])->where(['id' => '[0-9]+'])->name('user.edit');
      Route::post('{id}/update', [UserController::class, 'update'])->where(['id' => '[0-9]+'])->name('user.update');
      Route::get('{id}/delete', [UserController::class, 'delete'])->where(['id' => '[0-9]+'])->name('user.delete');
      Route::delete('{id}/destroy', [UserController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('user.destroy');
   });


   Route::group(['prefix' => 'user/catalogue'], function () {
      Route::get('index', [UserCatalogueController::class, 'index'])->name('user.catalogue.index');
      Route::get('create', [UserCatalogueController::class, 'create'])->name('user.catalogue.create');
      Route::post('store', [UserCatalogueController::class, 'store'])->name('user.catalogue.store');
      Route::get('{id}/edit', [UserCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('user.catalogue.edit');
      Route::post('{id}/update', [UserCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('user.catalogue.update');
      Route::get('{id}/delete', [UserCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('user.catalogue.delete');
      Route::delete('{id}/destroy', [UserCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('user.catalogue.destroy');
      Route::get('permission', [UserCatalogueController::class, 'permission'])->name('user.catalogue.permission');
      Route::post('updatePermission', [UserCatalogueController::class, 'updatePermission'])->name('user.catalogue.updatePermission');
   });

   Route::group(['prefix' => 'customer'], function () {
      Route::get('index', [CustomerController::class, 'index'])->name('customer.index');
      Route::get('create', [CustomerController::class, 'create'])->name('customer.create');
      Route::post('store', [CustomerController::class, 'store'])->name('customer.store');
      Route::get('{id}/edit', [CustomerController::class, 'edit'])->where(['id' => '[0-9]+'])->name('customer.edit');
      Route::post('{id}/update', [CustomerController::class, 'update'])->where(['id' => '[0-9]+'])->name('customer.update');
      Route::get('{id}/delete', [CustomerController::class, 'delete'])->where(['id' => '[0-9]+'])->name('customer.delete');
      Route::delete('{id}/destroy', [CustomerController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('customer.destroy');
   });


   Route::group(['prefix' => 'customer/catalogue'], function () {
      Route::get('index', [CustomerCatalogueController::class, 'index'])->name('customer.catalogue.index');
      Route::get('create', [CustomerCatalogueController::class, 'create'])->name('customer.catalogue.create');
      Route::post('store', [CustomerCatalogueController::class, 'store'])->name('customer.catalogue.store');
      Route::get('{id}/edit', [CustomerCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('customer.catalogue.edit');
      Route::post('{id}/update', [CustomerCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('customer.catalogue.update');
      Route::get('{id}/delete', [CustomerCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('customer.catalogue.delete');
      Route::delete('{id}/destroy', [CustomerCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('customer.catalogue.destroy');
      Route::get('permission', [CustomerCatalogueController::class, 'permission'])->name('customer.catalogue.permission');
      Route::post('updatePermission', [CustomerCatalogueController::class, 'updatePermission'])->name('customer.catalogue.updatePermission');
   });

   Route::group(['prefix' => 'language'], function () {
      Route::get('index', [LanguageController::class, 'index'])->name('language.index')->middleware(['admin','locale']);
      Route::get('create', [LanguageController::class, 'create'])->name('language.create');
      Route::post('store', [LanguageController::class, 'store'])->name('language.store');
      Route::get('{id}/edit', [LanguageController::class, 'edit'])->where(['id' => '[0-9]+'])->name('language.edit');
      Route::post('{id}/update', [LanguageController::class, 'update'])->where(['id' => '[0-9]+'])->name('language.update');
      Route::get('{id}/delete', [LanguageController::class, 'delete'])->where(['id' => '[0-9]+'])->name('language.delete');
      Route::delete('{id}/destroy', [LanguageController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('language.destroy');
      Route::get('{id}/switch', [LanguageController::class, 'swicthBackendLanguage'])->where(['id' => '[0-9]+'])->name('language.switch');
      Route::get('{id}/{languageId}/{model}/translate', [LanguageController::class, 'translate'])->where(['id' => '[0-9]+', 'languageId' => '[0-9]+'])->name('language.translate');
      Route::post('storeTranslate', [LanguageController::class, 'storeTranslate'])->name('language.storeTranslate');
   });

   Route::group(['prefix' => 'generate'], function () {
      Route::get('index', [GenerateController::class, 'index'])->name('generate.index')->middleware(['admin','locale']);
      Route::get('create', [GenerateController::class, 'create'])->name('generate.create');
      Route::post('store', [GenerateController::class, 'store'])->name('generate.store');
      Route::get('{id}/edit', [GenerateController::class, 'edit'])->where(['id' => '[0-9]+'])->name('generate.edit');
      Route::post('{id}/update', [GenerateController::class, 'update'])->where(['id' => '[0-9]+'])->name('generate.update');
      Route::get('{id}/delete', [GenerateController::class, 'delete'])->where(['id' => '[0-9]+'])->name('generate.delete');
      Route::delete('{id}/destroy', [GenerateController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('generate.destroy');
   });

   Route::group(['prefix' => 'system'], function () {
      Route::get('index', [SystemController::class, 'index'])->name('system.index');
      Route::post('store', [SystemController::class, 'store'])->name('system.store');
      Route::get('{languageId}/translate', [SystemController::class, 'translate'])->where(['languageId' => '[0-9]+'])->name('system.translate');
      Route::post('{languageId}/saveTranslate', [SystemController::class, 'saveTranslate'])->where(['languageId' => '[0-9]+'])->name('system.save.translate');
   });

   Route::group(['prefix' => 'review'], function () {
      Route::get('index', [ReviewController::class, 'index'])->name('review.index');
      Route::get('{id}/delete', [ReviewController::class, 'delete'])->where(['id' => '[0-9]+'])->name('review.delete');
      
   });

   Route::group(['prefix' => 'menu'], function () {
      Route::get('index', [MenuController::class, 'index'])->name('menu.index');
      Route::get('create', [MenuController::class, 'create'])->name('menu.create');
      Route::post('store', [MenuController::class, 'store'])->name('menu.store');
      Route::get('{id}/edit', [MenuController::class, 'edit'])->where(['id' => '[0-9]+'])->name('menu.edit');
      Route::get('{id}/editMenu', [MenuController::class, 'editMenu'])->where(['id' => '[0-9]+'])->name('menu.editMenu');
      Route::post('{id}/update', [MenuController::class, 'update'])->where(['id' => '[0-9]+'])->name('menu.update');
      Route::get('{id}/delete', [MenuController::class, 'delete'])->where(['id' => '[0-9]+'])->name('menu.delete');
      Route::delete('{id}/destroy', [MenuController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('menu.destroy');
      Route::get('{id}/children', [MenuController::class, 'children'])->where(['id' => '[0-9]+'])->name('menu.children');
      Route::post('{id}/saveChildren', [MenuController::class, 'saveChildren'])->where(['id' => '[0-9]+'])->name('menu.save.children');
      Route::get('{languageId}/{id}/translate', [MenuController::class, 'translate'])->where(['languageId' => '[0-9]+', 'id' => '[0-9]+'])->name('menu.translate');
      Route::post('{languageId}/saveTranslate', [MenuController::class, 'saveTranslate'])->where(['languageId' => '[0-9]+'])->name('menu.translate.save');
   });


   Route::group(['prefix' => 'post/catalogue'], function () {
      Route::get('index', [PostCatalogueController::class, 'index'])->name('post.catalogue.index');
      Route::get('create', [PostCatalogueController::class, 'create'])->name('post.catalogue.create');
      Route::post('store', [PostCatalogueController::class, 'store'])->name('post.catalogue.store');
      Route::get('{id}/edit', [PostCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('post.catalogue.edit');
      Route::post('{id}/update', [PostCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('post.catalogue.update');
      Route::get('{id}/delete', [PostCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('post.catalogue.delete');
      Route::delete('{id}/destroy', [PostCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('post.catalogue.destroy');
   });

   Route::group(['prefix' => 'post'], function () {
      Route::get('index', [PostController::class, 'index'])->name('post.index');
      Route::get('create', [PostController::class, 'create'])->name('post.create');
      Route::post('store', [PostController::class, 'store'])->name('post.store');
      Route::get('{id}/edit', [PostController::class, 'edit'])->where(['id' => '[0-9]+'])->name('post.edit');
      Route::post('{id}/update', [PostController::class, 'update'])->where(['id' => '[0-9]+'])->name('post.update');
      Route::get('{id}/delete', [PostController::class, 'delete'])->where(['id' => '[0-9]+'])->name('post.delete');
      Route::delete('{id}/destroy', [PostController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('post.destroy');
   });

   Route::group(['prefix' => 'permission'], function () {
      Route::get('index', [PermissionController::class, 'index'])->name('permission.index');
      Route::get('create', [PermissionController::class, 'create'])->name('permission.create');
      Route::post('store', [PermissionController::class, 'store'])->name('permission.store');
      Route::get('{id}/edit', [PermissionController::class, 'edit'])->where(['id' => '[0-9]+'])->name('permission.edit');
      Route::post('{id}/update', [PermissionController::class, 'update'])->where(['id' => '[0-9]+'])->name('permission.update');
      Route::get('{id}/delete', [PermissionController::class, 'delete'])->where(['id' => '[0-9]+'])->name('permission.delete');
      Route::delete('{id}/destroy', [PermissionController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('permission.destroy');
   });

   Route::group(['prefix' => 'slide'], function () {
      Route::get('index', [SlideController::class, 'index'])->name('slide.index');
      Route::get('create', [SlideController::class, 'create'])->name('slide.create');
      Route::post('store', [SlideController::class, 'store'])->name('slide.store');
      Route::get('{id}/edit', [SlideController::class, 'edit'])->where(['id' => '[0-9]+'])->name('slide.edit');
      Route::post('{id}/update', [SlideController::class, 'update'])->where(['id' => '[0-9]+'])->name('slide.update');
      Route::get('{id}/delete', [SlideController::class, 'delete'])->where(['id' => '[0-9]+'])->name('slide.delete');
      Route::delete('{id}/destroy', [SlideController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('slide.destroy');
   });

   Route::group(['prefix' => 'widget'], function () {
      Route::get('index', [WidgetController::class, 'index'])->name('widget.index');
      Route::get('create', [WidgetController::class, 'create'])->name('widget.create');
      Route::post('store', [WidgetController::class, 'store'])->name('widget.store');
      Route::get('{id}/edit', [WidgetController::class, 'edit'])->where(['id' => '[0-9]+'])->name('widget.edit');
      Route::post('{id}/update', [WidgetController::class, 'update'])->where(['id' => '[0-9]+'])->name('widget.update');
      Route::get('{id}/delete', [WidgetController::class, 'delete'])->where(['id' => '[0-9]+'])->name('widget.delete');
      Route::delete('{id}/destroy', [WidgetController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('widget.destroy');
      Route::get('{languageId}/{id}/translate', [WidgetController::class, 'translate'])->where(['id' => '[0-9]+', 'languageId' => '[0-9]+'])->name('widget.translate');
      Route::post('saveTranslate', [WidgetController::class, 'saveTranslate'])->name('widget.saveTranslate');
   });

   Route::group(['prefix' => 'source'], function () {
      Route::get('index', [SourceController::class, 'index'])->name('source.index');
      Route::get('create', [SourceController::class, 'create'])->name('source.create');
      Route::post('store', [SourceController::class, 'store'])->name('source.store');
      Route::get('{id}/edit', [SourceController::class, 'edit'])->where(['id' => '[0-9]+'])->name('source.edit');
      Route::post('{id}/update', [SourceController::class, 'update'])->where(['id' => '[0-9]+'])->name('source.update');
      Route::get('{id}/delete', [SourceController::class, 'delete'])->where(['id' => '[0-9]+'])->name('source.delete');
      Route::delete('{id}/destroy', [SourceController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('source.destroy');
      
   });

   Route::group(['prefix' => 'distribution'], function () {
      Route::get('index', [DistributionController::class, 'index'])->name('distribution.index');
      Route::get('create', [DistributionController::class, 'create'])->name('distribution.create');
      Route::post('store', [DistributionController::class, 'store'])->name('distribution.store');
      Route::get('{id}/edit', [DistributionController::class, 'edit'])->where(['id' => '[0-9]+'])->name('distribution.edit');
      Route::post('{id}/update', [DistributionController::class, 'update'])->where(['id' => '[0-9]+'])->name('distribution.update');
      Route::get('{id}/delete', [DistributionController::class, 'delete'])->where(['id' => '[0-9]+'])->name('distribution.delete');
      Route::delete('{id}/destroy', [DistributionController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('distribution.destroy');
      
   });


   Route::group(['prefix' => 'promotion'], function () {
      Route::get('index', [PromotionController::class, 'index'])->name('promotion.index');
      Route::get('create', [PromotionController::class, 'create'])->name('promotion.create');
      Route::post('store', [PromotionController::class, 'store'])->name('promotion.store');
      Route::get('{id}/edit', [PromotionController::class, 'edit'])->where(['id' => '[0-9]+'])->name('promotion.edit');
      Route::post('{id}/update', [PromotionController::class, 'update'])->where(['id' => '[0-9]+'])->name('promotion.update');
      Route::get('{id}/delete', [PromotionController::class, 'delete'])->where(['id' => '[0-9]+'])->name('promotion.delete');
      Route::delete('{id}/destroy', [PromotionController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('promotion.destroy');
   });

   Route::group(['prefix' => 'product/catalogue'], function () {
      Route::get('index', [ProductCatalogueController::class, 'index'])->name('product.catalogue.index');
      Route::get('create', [ProductCatalogueController::class, 'create'])->name('product.catalogue.create');
      Route::post('store', [ProductCatalogueController::class, 'store'])->name('product.catalogue.store');
      Route::get('{id}/edit', [ProductCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('product.catalogue.edit');
      Route::post('{id}/update', [ProductCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('product.catalogue.update');
      Route::get('{id}/delete', [ProductCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('product.catalogue.delete');
      Route::delete('{id}/destroy', [ProductCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('product.catalogue.destroy');
   });
   Route::group(['prefix' => 'product'], function () {
      Route::get('index', [ProductController::class, 'index'])->name('product.index');
      Route::get('create', [ProductController::class, 'create'])->name('product.create');
      Route::post('store', [ProductController::class, 'store'])->name('product.store');
      Route::get('{id}/edit', [ProductController::class, 'edit'])->where(['id' => '[0-9]+'])->name('product.edit');
      Route::post('{id}/update', [ProductController::class, 'update'])->where(['id' => '[0-9]+'])->name('product.update');
      Route::get('{id}/delete', [ProductController::class, 'delete'])->where(['id' => '[0-9]+'])->name('product.delete');
      Route::delete('{id}/destroy', [ProductController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('product.destroy');
   });
   Route::group(['prefix' => 'attribute/catalogue'], function () {
      Route::get('index', [AttributeCatalogueController::class, 'index'])->name('attribute.catalogue.index');
      Route::get('create', [AttributeCatalogueController::class, 'create'])->name('attribute.catalogue.create');
      Route::post('store', [AttributeCatalogueController::class, 'store'])->name('attribute.catalogue.store');
      Route::get('{id}/edit', [AttributeCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('attribute.catalogue.edit');
      Route::post('{id}/update', [AttributeCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('attribute.catalogue.update');
      Route::get('{id}/delete', [AttributeCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('attribute.catalogue.delete');
      Route::delete('{id}/destroy', [AttributeCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('attribute.catalogue.destroy');
   });
   
   Route::group(['prefix' => 'attribute'], function () {
      Route::get('index', [AttributeController::class, 'index'])->name('attribute.index');
      Route::get('create', [AttributeController::class, 'create'])->name('attribute.create');
      Route::post('store', [AttributeController::class, 'store'])->name('attribute.store');
      Route::get('{id}/edit', [AttributeController::class, 'edit'])->where(['id' => '[0-9]+'])->name('attribute.edit');
      Route::post('{id}/update', [AttributeController::class, 'update'])->where(['id' => '[0-9]+'])->name('attribute.update');
      Route::get('{id}/delete', [AttributeController::class, 'delete'])->where(['id' => '[0-9]+'])->name('attribute.delete');
      Route::delete('{id}/destroy', [AttributeController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('attribute.destroy');
   });

   Route::group(['prefix' => 'order'], function () {
      Route::get('index', [OrderController::class, 'index'])->name('order.index');
      Route::get('{id}/detail', [OrderController::class, 'detail'])->where(['id' => '[0-9]+'])->name('order.detail');
   });

   /*CRM*/

   Route::group(['prefix' => 'agency'], function () {
      Route::get('index', [AgencyController::class, 'index'])->name('agency.index');
      Route::get('create', [AgencyController::class, 'create'])->name('agency.create');
      Route::post('store', [AgencyController::class, 'store'])->name('agency.store');
      Route::get('{id}/edit', [AgencyController::class, 'edit'])->where(['id' => '[0-9]+'])->name('agency.edit');
      Route::post('{id}/update', [AgencyController::class, 'update'])->where(['id' => '[0-9]+'])->name('agency.update');
      Route::get('{id}/delete', [AgencyController::class, 'delete'])->where(['id' => '[0-9]+'])->name('agency.delete');
      Route::delete('{id}/destroy', [AgencyController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('agency.destroy');
   });

   Route::group(['prefix' => 'construction'], function () {
      Route::get('index', [ConstructionController::class, 'index'])->name('construction.index');
      Route::get('create', [ConstructionController::class, 'create'])->name('construction.create');
      Route::post('store', [ConstructionController::class, 'store'])->name('construction.store');
      Route::get('{id}/edit', [ConstructionController::class, 'edit'])->where(['id' => '[0-9]+'])->name('construction.edit');
      Route::post('{id}/update', [ConstructionController::class, 'update'])->where(['id' => '[0-9]+'])->name('construction.update');
      Route::get('{id}/delete', [ConstructionController::class, 'delete'])->where(['id' => '[0-9]+'])->name('construction.delete');
      Route::delete('{id}/destroy', [ConstructionController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('construction.destroy');
      Route::get('warranty', [ConstructionController::class, 'warranty'])->name('construction.warranty');
   });


   Route::group(['prefix' => 'report'], function () {
      Route::get('time', [ReportController::class, 'time'])->name('report.time');
      Route::get('product', [ReportController::class, 'product'])->name('report.product');
      Route::get('customer', [ReportController::class, 'customer'])->name('report.customer');
   });

//@@new-module@@





   /* AJAX */
  
   Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');
   Route::post('ajax/dashboard/changeStatusAll', [AjaxDashboardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll');
   Route::get('ajax/dashboard/getMenu', [AjaxDashboardController::class, 'getMenu'])->name('ajax.dashboard.getMenu');
   
   Route::get('ajax/dashboard/findPromotionObject', [AjaxDashboardController::class, 'findPromotionObject'])->name('ajax.dashboard.findPromotionObject');
   Route::get('ajax/dashboard/getPromotionConditionValue', [AjaxDashboardController::class, 'getPromotionConditionValue'])->name('ajax.dashboard.getPromotionConditionValue');
   Route::get('ajax/attribute/getAttribute', [AjaxAttributeController::class, 'getAttribute'])->name('ajax.attribute.getAttribute');
   Route::get('ajax/attribute/loadAttribute', [AjaxAttributeController::class, 'loadAttribute'])->name('ajax.attribute.getAttribute');
   Route::post('ajax/menu/createCatalogue', [AjaxMenuController::class, 'createCatalogue'])->name('ajax.menu.createCatalogue');
   Route::post('ajax/menu/drag', [AjaxMenuController::class, 'drag'])->name('ajax.menu.drag');
   Route::post('ajax/menu/deleteMenu', [AjaxMenuController::class, 'deleteMenu'])->name('ajax.menu.deleteMenu');
   Route::post('ajax/slide/order', [AjaxSlideController::class, 'order'])->name('ajax.slide.order');
   Route::get('ajax/product/loadProductPromotion', [AjaxProductController::class, 'loadProductPromotion'])->name('ajax.loadProductPromotion');
   
   Route::get('ajax/source/getAllSource', [AjaxSourceController::class, 'getAllSource'])->name('ajax.getAllSource');
   Route::post('ajax/order/update', [AjaxOrderController::class, 'update'])->name('ajax.order.update');
   Route::get('ajax/order/chart', [AjaxOrderController::class, 'chart'])->name('ajax.order.chart');

   Route::post('ajax/construct/createAgency', [AjaxConstructController::class,'createAgency'])->name('ajax.construct.createAgency');
   Route::post('ajax/construct/createCustomer', [AjaxCustomerController::class,'createCustomer'])->name('ajax.construct.createCustomer');
   Route::post('ajax/product/deleteProduct', [AjaxConstructController::class, 'deleteProduct'])->name('ajax.product.deleteProduct');
   Route::get('ajax/dashboard/findInformationObject', [AjaxDashboardController::class, 'findInformationObject'])->name('ajax.findInformationObject');
});


Route::get('admin', [AuthController::class, 'index'])->name('auth.admin')->middleware('login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
