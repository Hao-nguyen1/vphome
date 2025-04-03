<?php
namespace App\Classes;

class Momo{

    public function __construct(){

    }

    public function payment($order){
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $momoConfig = momoConfig();

        $partnerCode = $momoConfig['partnerCode'];
        $accessKey = $momoConfig['accessKey'];
        $secretKey = $momoConfig['secretKey'];
        $orderInfo = (!empty($order->description)) ? $order->description : 'Thanh toán đơn hàng #'.$order->code.' qua Momo';
        $amount = (string)($order->cart['cartTotal'] - $order->promotion['discount']);
        $orderId = $order->code;
        

        $ipnUrl = write_url('return/ipn');
        $redirectUrl = write_url('return/momo');
        $extraData = "";

        
        $requestId = time(). "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );


        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result,true);  

        $jsonResult['errorCode'] = $jsonResult['resultCode'];

        $jsonResult['url'] = $jsonResult['payUrl'];
        return $jsonResult;

    }
    
	
}
