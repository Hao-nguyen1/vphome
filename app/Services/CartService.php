<?php

namespace App\Services;

use App\Services\Interfaces\CartServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface as PromotionRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface  as ProductVariantRepository;
use Cart;
use App\Mail\OrderMail;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class CartService  implements CartServiceInterface
{

    protected $productRepository;
    protected $productVariantRepository;
    protected $promotionRepository;
    protected $orderRepository;
    protected $productService;

    public function __construct(
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository,
        PromotionRepository $promotionRepository,
        OrderRepository $orderRepository,
        ProductService $productService,
    ){
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->promotionRepository = $promotionRepository;
        $this->orderRepository = $orderRepository;
        $this->productService = $productService;
    }

    

    public function create($request, $language = 1){
        try{
            $payload = $request->input();
            $product = $this->productRepository->findById($payload['id'], ['*'], [
                'languages' => function($query) use ($language) {
                    $query->where('language_id',  $language);
                }
            ]);
            $data = [
                'id' => $product->id,
                'name' => $product->languages->first()->pivot->name,
                'qty' => $payload['quantity'],
            ];
            if(isset($payload['attribute_id']) && count($payload['attribute_id'])){
                $attributeId = sortAttributeId($payload['attribute_id']);
                $variant = $this->productVariantRepository->findVariant($attributeId, $product->id, $language);
                $variantPromotion = $this->promotionRepository->findPromotionByVariantUuid($variant->uuid);
                $variantPrice = getVariantPrice($variant, $variantPromotion);

                $data['id'] =  $product->id.'_'.$variant->uuid;
                $data['name'] = $product->languages->first()->pivot->name.' '.$variant->languages()->first()->pivot->name;
                $data['price'] = ($variantPrice['priceSale'] > 0) ? $variantPrice['priceSale'] : $variantPrice['price'];
                $data['options'] = [
                    'attribute' => $payload['attribute_id'],
                ];
            }else{
                $product = $this->productService->combineProductAndPromotion([$product->id], $product, true); 
                $price = getPrice($product);
                $data['price'] = ($price['priceSale'] > 0) ? $price['priceSale'] : $price['price'];
            }

            Cart::instance('shopping')->add($data);

            return true;
        }catch(\Exception $e ){
            echo $e->getMessage().$e->getCode();die();
            return false;
        }
    }

    public function update($request){
        try{
            $payload = $request->input();
            Cart::instance('shopping')->update($payload['rowId'], $payload['qty']);
            $cartCaculate = $this->cartAndPromotion();
            $cartItem = Cart::instance('shopping')->get($payload['rowId']);
            $cartCaculate['cartItemSubTotal'] = $cartItem->qty * $cartItem->price;

            return $cartCaculate;
        }catch(\Exception $e ){
            echo $e->getMessage().$e->getCode();die();
            return false;
        }
    }

    public function delete($request){
        try{
            $payload = $request->input();
            Cart::instance('shopping')->remove($payload['rowId']);
            $cartCaculate = $this->cartAndPromotion();
            return $cartCaculate;
        }catch(\Exception $e ){
            echo $e->getMessage().$e->getCode();die();
            return false;
        }
    }


    public function order($request, $system){
        DB::beginTransaction();
        try{
            $payload = $this->request($request);
            $order = $this->orderRepository->create($payload, ['products']);
            if($order->id > 0){
                $this->createOrderProduct($payload, $order, $request);
                $this->mail($order, $system);
                Cart::instance('shopping')->destroy();
            }
            DB::commit();
            return [
                'order' => $order,
                'flag' => TRUE,
            ];
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return [
                'order' => null,
                'flag' => false,
            ];
        }
    }

    private function mail($order, $sytem){
        $to = $order->email;
        $cc = $sytem['contact_email'];
        $carts = Cart::instance('shopping')->content();
        $carts = $this->remakeCart($carts);
        $cartCaculate = $this->cartAndPromotion();
        $cartPromotion = $this->cartPromotion($cartCaculate['cartTotal']);
        $data = [
            'order' => $order, 
            'carts' => $carts, 
            'cartCaculate' => $cartCaculate, 
            'cartPromotion' => $cartPromotion
        ];
        
        \Mail::to($to)->cc($cc)->send(new OrderMail($data));

    }


    

    private function createOrderProduct($payload, $order, $request){
        $carts = Cart::instance('shopping')->content();
        $carts = $this->remakeCart($carts);
        $temp = [];
        if(!is_null($carts)){
            foreach($carts as $key => $val){
                $extract = explode('_', $val->id);
                $temp[] = [
                    'product_id' => $extract[0],
                    'uuid' => ($extract[1]) ?? null,
                    'name' => $val->name,
                    'qty' => $val->qty,
                    'price' => $val->price,
                    'priceOriginal' => $val->priceOriginal,
                    'option' => json_encode($val->options), 
                ];
            }     
        }
        $order->products()->sync($temp);
    }

    private function request($request){
        
        $cartCaculate = $this->reCaculateCart();
        $cartPromotion = $this->cartPromotion($cartCaculate['cartTotal']);

        $payload = $request->except(['_token', 'voucher', 'create']);
        $payload['code'] = time();
        $payload['cart'] = $cartCaculate;
        $payload['promotion']['discount'] = $cartPromotion['discount'] ?? '';
        $payload['promotion']['name'] = $cartPromotion['selectedPromotion']->name ?? '';
        $payload['promotion']['code'] = $cartPromotion['selectedPromotion']->code ?? '';
        $payload['promotion']['startDate'] = $cartPromotion['selectedPromotion']->startDate ?? '';
        $payload['promotion']['endDate'] = $cartPromotion['selectedPromotion']->endDate ?? '';
        $payload['confirm'] = 'pending';
        $payload['delivery'] = 'pending';
        $payload['payment'] = 'unpaid';
        return $payload;
    }

    private function cartAndPromotion(){
        $cartCaculate = $this->reCaculateCart();
        $cartPromotion = $this->cartPromotion($cartCaculate['cartTotal']);
        $cartCaculate['cartTotal'] = $cartCaculate['cartTotal'] - $cartPromotion['discount'];
        $cartCaculate['cartDiscount'] = $cartPromotion['discount'];

        return $cartCaculate;
    }

    public function reCaculateCart(){
        $carts = Cart::instance('shopping')->content();
        $total = 0;
        $totalItems = 0;
        foreach($carts as $cart){
            $total = $total + $cart->price * $cart->qty;
            $totalItems = $totalItems + $cart->qty;
        }
        return [
            'cartTotal' => $total,
            'cartTotalItems' => $totalItems
        ];
    }



    public function remakeCart($carts){
        $cartId = $carts->pluck('id')->all();
        $temp = [];
        $objects = [];
        if(count($cartId)){
            foreach($cartId as $key => $val){
                $extract = explode('_', $val);
                if(count($extract) > 1){
                    $temp['variant'][] = $extract[1];
                }else{
                    $temp['product'][] = $extract[0];
                }
            }

            
            if(isset($temp['variant'])){
                $objects['variants'] = $this->productVariantRepository->findByCondition(
                    [], true, [], ['id', 'desc'], ['whereIn' => $temp['variant'], 'whereInField' => 'uuid']
                )->keyBy('uuid');
            }
            
            if(isset($temp['product'])){
                $objects['products'] = $this->productRepository->findByCondition(
                    [
                        config('apps.general.defaultPublish')
                    ], true, [], ['id', 'desc'], ['whereIn' => $temp['product'], 'whereInField' => 'id']
                )->keyBy('id');
            }
           

            foreach($carts as $keyCart => $cart){
                $explode = explode('_', $cart->id);
                $objectId = $explode[1] ?? $explode[0];
                if (isset($objects['variants'][$objectId])) {
                    $variantItem = $objects['variants'][$objectId];
                    $variantImage = explode(',' ,$variantItem->album)[0] ?? null;
                    $cart->setImage($variantImage)->setPriceOriginal($variantItem->price);
                } elseif (isset($objects['products'][$objectId])) {
                    $productItem = $objects['products'][$objectId];
                    $cart->setImage($productItem->image)->setPriceOriginal($productItem->price);

                }
            }

        }

        return $carts;
    }

    public function cartPromotion($cartTotal = 0){
        $maxDiscount = 0;
        $selectedPromotion = null;
        $promotions = $this->promotionRepository->getPromotionByCartTotal();
        if(!is_null($promotions)){
            foreach($promotions as $promotion){
                $discount = $promotion->discountInformation['info'];
                $amountFrom = $discount['amountFrom'] ?? [];
                $amountTo = $discount['amountTo'] ?? [];
                $amountValue = $discount['amountValue'] ?? [];
                $amountType = $discount['amountType'] ?? [];


                if(!empty($amountFrom) && count($amountFrom) == count($amountTo) && count($amountTo) == count($amountValue)){
                    for($i = 0; $i < count($amountFrom); $i++){
                        $currentAmountFrom = convert_price($amountFrom[$i]);
                        $currentAmountTo = convert_price($amountTo[$i]);
                        $currentAmountValue = convert_price($amountValue[$i]);
                        $currentAmountType = $amountType[$i];
                        if($cartTotal > $currentAmountFrom && ($cartTotal <= $currentAmountTo ) || $cartTotal > $currentAmountTo){

                            if($currentAmountType == 'cash'){
                                $maxDiscount = max($maxDiscount, $currentAmountValue);
                            }else if($currentAmountType == 'percent'){
                                $discountValue = ($currentAmountValue/100)*$cartTotal;
                                $maxDiscount = max($maxDiscount, $discountValue);
                            }
                            $selectedPromotion = $promotion;
                        }

                    }
                }
            }
        }
        return [
            'discount' => $maxDiscount,
            'selectedPromotion' => $selectedPromotion
        ];
    }
   
}
