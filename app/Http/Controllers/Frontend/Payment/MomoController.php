<?php

namespace App\Http\Controllers\Frontend\Payment;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\OrderRepositoryInterface  as OrderRepository;
use App\Services\Interfaces\OrderServiceInterface  as OrderService;


class MomoController extends FrontendController
{
  
    protected $orderRepository;
    protected $orderService;

    public function __construct(
        OrderRepository $orderRepository,
        OrderService $orderService,
    ){
       
        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
        parent::__construct();
    }


    public function momo_return(Request $request){


        $momoConfig = momoConfig();

        $secretKey = $momoConfig['secretKey']; //Put your secret key in there
        $partnerCode = $momoConfig['partnerCode']; //Put your secret key in there
        $accessKey = $momoConfig['accessKey']; //Put your secret key in there

        if (!empty($_GET)) {
           
            $rawData = "accessKey=".$accessKey;
            $rawData .= "&amount=".$_GET['amount'];
            $rawData .= "&extraData=".$_GET['extraData'];
            $rawData .= "&message=".$_GET['message'];
            $rawData .= "&orderId=".$_GET['orderId'];
            $rawData .= "&orderInfo=".$_GET['orderInfo'];
            $rawData .= "&orderType=".$_GET['orderType'];
            $rawData .= "&partnerCode=".$_GET['partnerCode'];
            $rawData .= "&payType=".$_GET['payType'];
            $rawData .= "&requestId=".$_GET['requestId'];
            $rawData .= "&responseTime=".$_GET['responseTime'];
            $rawData .= "&resultCode=".$_GET['resultCode'];
            $rawData .= "&transId=".$_GET['transId'];


           
            $partnerSignature = hash_hmac("sha256", $rawData, $secretKey);
            $m2signature = $_GET['signature'];


            if($partnerSignature == $m2signature){
                $orderId = $_GET['orderId'];
                $order = $this->orderRepository->findByCondition([
                    ['code', '=', $orderId],
                ], false, ['products']);

                $payload['payment'] = 'paid';
                $payload['confirm'] = 'confirm';
                $flag = $this->orderService->updatePaymentOnline($payload, $order);
            }

            $momo = [
                'm2signature' => $m2signature,
                'partnerSignature' => $partnerSignature,
                'message' => $_GET['message'],
            ];
            
            $orderId = $_GET['orderId'];
            $order = $this->orderRepository->findByCondition([
                ['code', '=', $orderId],
            ], false, ['products']);

            $system = $this->system;
            $seo = [
                'meta_title' => 'Thông tin thanh toán mã đơn hàng #'.$orderId,
                'meta_keyword' => '',
                'meta_description' => '',
                'meta_image' => '',
                'canonical' => write_url('cart/success', TRUE, TRUE),
            ];
            
            $template = 'frontend.cart.component.momo';
            return view('frontend.cart.success', compact(
                'seo',
                'system',
                'order',
                'template',
                'momo'
            ));
        }
    
    }

    public function momo_ipn(){
        http_response_code(200); //200 - Everything will be 200 Oke

        $momoConfig = momoConfig();

        $secretKey = $momoConfig['secretKey']; //Put your secret key in there
        if (!empty($_POST)) {
            $response = array();
            try {
               
                //Checksum
                $rawData = "accessKey=".$accessKey;
                $rawData .= "&amount=".$_POST['amount'];
                $rawData .= "&extraData=".$_POST['extraData'];
                $rawData .= "&message=".$_POST['message'];
                $rawData .= "&orderId=".$_POST['orderId'];
                $rawData .= "&orderInfo=".$_POST['orderInfo'];
                $rawData .= "&orderType=".$_POST['orderType'];
                $rawData .= "&partnerCode=".$_POST['partnerCode'];
                $rawData .= "&payType=".$_POST['payType'];
                $rawData .= "&requestId=".$_POST['requestId'];
                $rawData .= "&responseTime=".$_POST['responseTime'];
                $rawData .= "&resultCode=".$_POST['resultCode'];
                $rawData .= "&transId=".$_POST['transId'];

                $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);

                if ($m2signature == $partnerSignature) {

                    $order = $this->orderRepository->findByCondition([
                        ['code', '=', $orderId],
                    ], false, ['products']);

                    $payload['payment'] = 'paid';
                    $payload['confirm'] = 'confirm';
                    $flag = $this->orderService->updatePaymentOnline($payload, $order);

                } else {
                    $result = '<div class="alert alert-danger">This transaction could be hacked, please check your signature and returned signature</div>';
                }

            } catch (Exception $e) {
                echo $response['message'] = $e;
            }

            $debugger = array();
            $debugger['rawData'] = $rawHash;
            $debugger['momoSignature'] = $m2signature;
            $debugger['partnerSignature'] = $partnerSignature;

            if ($m2signature == $partnerSignature) {
                $response['message'] = "Received payment result success";
            } else {
                $response['message'] = "ERROR! Fail checksum";
            }
            $response['debugger'] = $debugger;
            echo json_encode($response);
        }
    }

  

}
