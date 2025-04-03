<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Services\Interfaces\CustomerServiceInterface as CustomerService;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;


class ReportController extends Controller
{
    protected $orderService;
    protected $customerService;
    protected $orderRepository;

    public function __construct(
        OrderService $orderService,
        CustomerService $customerService,
        OrderRepository $orderRepository,
    ){
        $this->orderService = $orderService;
        $this->customerService = $customerService;
        $this->orderRepository = $orderRepository;
    }

    public function time(Request $request){
        $user = Auth::guard('web')->User();
        $orderStatistic = $this->orderService->statistic(); 
        $customerStatistic = $this->customerService->statistic();
        $reports = [];
        if($request->input('startDate')){
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $startDate = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $startDate)));
            $endDate = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $endDate)));
            $reports = $this->orderRepository->getReportTime($startDate, $endDate);
        }
        $config = $this->config();
        $template = 'backend.report.time';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'orderStatistic',
            'customerStatistic',
            'user',
            'reports'
        ));
    }
    
    public function product(Request $request){
        $user = Auth::guard('web')->User();
        $orderStatistic = $this->orderService->statistic(); 
        $customerStatistic = $this->customerService->statistic();
        $reports = [];
        if($request->input('startDate')){
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $startDate = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $startDate)));
            $endDate = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $endDate)));
            $reports = $this->orderRepository->getProductReportTime($startDate, $endDate);
        }
        $config = $this->config();
        $template = 'backend.report.product';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'orderStatistic',
            'customerStatistic',
            'user',
            'reports'
        ));
    }

    public function customer(Request $request){
        $user = Auth::guard('web')->User();
        $orderStatistic = $this->orderService->statistic(); 
        $customerStatistic = $this->customerService->statistic();
        $reports = [];
        $totalSumaryRevenue = [];
        if($request->input('startDate')){
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
            $startDate = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $startDate)));
            $endDate = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $endDate)));
            $totalRevenue = $this->orderRepository->getTotalRevenueReportTime($startDate, $endDate);
            $totalSumaryRevenue = convert_price($totalRevenue[0]['sum_revenue'] - $totalRevenue[0]['sum_discount'], true);
            $reports = $this->orderRepository->getCustomerReportTime($startDate, $endDate);
        }
        $config = $this->config();
        $template = 'backend.report.customer';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'orderStatistic',
            'customerStatistic',
            'user',
            'reports',
            'totalSumaryRevenue'
        ));
    }


    private function config(){
        return [
            'js' => [
                'backend/js/plugins/chartJs/Chart.min.js',
                'backend/library/dashboard.js',
                'backend/library/report.js',
                'backend/plugins/datetimepicker-master/build/jquery.datetimepicker.full.js',
            ],
            'css' => [
                'backend/plugins/datetimepicker-master/build/jquery.datetimepicker.min.css',
            ]
        ];
    }

}
