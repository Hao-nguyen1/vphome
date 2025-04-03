<?php
namespace App\Classes;

class Zalo{

    public function __construct(){

    }

    public function payment($order){
        $zaloConfig = zaloConfig();

        $config = [
            "appid" => $zaloConfig['appid'],
            "key1" => $zaloConfig['key1'],
            "key2" => $zaloConfig['key2'],
            "endpoint" => "https://sbgateway.zalopay.vn/api/getlistmerchantbanks"
        ];

        $embeddata = [
            "merchantinfo" => "embeddata123"
        ];

        $cartTotal = $order->cart['cartTotal'] - $order->promotion['discount'];
        $items = [
            [ "itemid" => $order->id, "itemname" => "Thanh toán đơn hàng", "itemprice" => $cartTotal, "itemquantity" => 1 ]
        ];
        $order = [
            "appid" => $config["appid"],
            "apptime" => round(microtime(true) * 1000), // miliseconds
            "apptransid" => date("ymd")."_".uniqid(), // mã giao dich có định dạng yyMMdd_xxxx
            "appuser" => "demo",
            "item" => json_encode($items, JSON_UNESCAPED_UNICODE),
            "embeddata" => json_encode($embeddata, JSON_UNESCAPED_UNICODE),
            "amount" => 50000,
            "description" => $order->description,
            "bankcode" => "zalopayapp"
        ];

        $data = $order["appid"]."|".$order["apptransid"]."|".$order["appuser"]."|".$order["amount"]
        ."|".$order["apptime"]."|".$order["embeddata"]."|".$order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $config["key1"]);

        $context = stream_context_create([
            "http" => [
              "header" => "Content-type: application/x-www-form-urlencoded\r\n",
              "method" => "POST",
              "content" => http_build_query($order)
            ]
        ]);

        $resp = file_get_contents($config["endpoint"], false, $context);
        $result = json_decode($resp, true);

        dd($result);
       
    }
    
	
}
