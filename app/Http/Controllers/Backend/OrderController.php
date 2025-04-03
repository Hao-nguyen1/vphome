<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderRepository;

    public function __construct(
        OrderService $orderService,
        OrderRepository $orderRepository,
        ProvinceRepository $provinceRepository
    ) {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'order.index');
        $orders = $this->orderService->paginate($request);
        $config = [
            'js' => [
                'backend/library/order.js',
                'backend/js/plugins/switchery/switchery.js',
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',
                'backend/js/plugins/daterangepicker/daterangepicker.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'backend/css/plugins/daterangepicker/daterangepicker-bs3.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Order'
        ];
        $config['seo'] = __('messages.order');
        $template = 'backend.order.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'orders'
        ));
    }

    public function detail(Request $request, $id)
    {
        $order = $this->orderRepository->getOrderById($id, ['products']);
        $order = $this->orderService->getOrderItemImage($order);

        $provinces = $this->provinceRepository->all();
        $config = [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'backend/library/order.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],
        ];
        
        $config['seo'] = __('messages.order');
        $template = 'backend.order.detail';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'order',
            'provinces'
        ));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('modules', 'order.update');

        $payload = $request->only(['confirm', 'payment', 'delivery']); // Lấy các trường cần cập nhật
        $request->merge(['id' => $id, 'payload' => $payload]);

        $success = $this->orderService->update($request);

        if ($success) {
            return redirect()->route('order.index')->with('success', 'Cập nhật đơn hàng thành công');
        }
        return redirect()->route('order.index')->with('error', 'Cập nhật đơn hàng thất bại');
    }
}