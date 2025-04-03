<?php
namespace App\Classes;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class Paypal{

    public function __construct(){

    }

    public function payment($order){

        $usd = 23000;
        $cartTotal = $order->cart['cartTotal'] - $order->promotion['discount'];

        $paypalValue = number_format($cartTotal/$usd, 2, '.', '');

        $provider = new PayPalClient;
        $provider = \PayPal::setProvider();

        $accessToken = $provider->getAccessToken();

        $data = [
            "intent" => "CAPTURE",
            "application_context" => [
                'return_url' => route('paypal.success', ['orderId' => $order->id]),
                'cancel_url' => route('paypal.cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $paypalValue
                    ]
                ]
            ]
        ];
                   


        $response = $provider->createOrder($data);
        $res['url'] = '';
        if(!empty($response['id']) && $response['id'] != ''){
            foreach($response['links'] as $key => $val){
                if($val['rel'] == 'approve'){
                    $res['url'] = $val['href'];
                    $res['errorCode'] = 0;
                }
            }
        }
        return $res;
    }
    
	
}
