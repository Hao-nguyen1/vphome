
@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['detail']['title']])

<div class="order-wrapper">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="ibox-title-left">
                            <span>Chi tiết đơn hàng #{{ $order->code }}</span>
                            <span class="badge">
                                <div class="badge__tip"></div>
                                <div class="badge-text"> {{ __('cart.delivery')[$order->delivery] }}</div>
                            </span>
                            <span class="badge">
                                <div class="badge__tip"></div>
                                <div class="badge-text"> {{ __('cart.payment')[$order->payment] }}</div>
                            </span>
                        </div>
                        <div class="ibox-title-right">
                            Nguồn: Website
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table-order">
                        <tbody>
                            @foreach($order->products as $key => $val)
                            @php
                                $name = $val->pivot->name;
                                $qty = $val->pivot->qty;
                                $price = convert_price($val->pivot->price, true);
                                $priceOriginal = convert_price($val->pivot->priceOriginal, true);
                                $subtotal = convert_price($val->pivot->price * $qty, true);
                                $image = image($val->image);
                            @endphp
                            <tr class="order-item">
                                <td>
                                   <div class="image">
                                        <span class="image img-scaledown"><img src="{{ $image; }}" alt=""></span>
                                    </div> 
                                </td>
                                <td style="width:285px;">
                                    <div class="order-item-name" title=""{{ $name }}">{{ $name }}</div>
                                    <div class="order-item-voucher">Mã giảm giá: Không có</div>
                                </td>
                                <td>
                                    <div class="order-item-price">{{ $price }} ₫</div>
                                </td>
                                <td>
                                    <div class="order-item-times">x</div>
                                </td>
                                <td>
                                    <div class="order-item-qty">{{ $qty }}</div>
                                </td>
                                <td>
                                    <div class="order-item-subtotal">
                                        {{ $subtotal }} ₫
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" class="text-right">Tổng tạm</td>
                                <td class="text-right">{{ convert_price($order->cart['cartTotal'], true) }} ₫</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">Giảm giá</td>
                                <td class="text-right">- {{ convert_price($order->promotion['discount'], true) }} ₫</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">Vận chuyển</td>
                                <td class="text-right">0 ₫</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right" ><strong>Tổng cuối</strong></td>
                                <td class="text-right" style="font-size:18px;"><strong style="color:blue;">{{ convert_price($order->cart['cartTotal'] - $order->promotion['discount'], true) }} ₫</strong></td>
                            </tr>
                        </tbody>
                        
                    </table>
                </div>
                <div class="payment-confirm confirm-box">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="uk-flex uk-flex-middle">
                            <span class="icon"><img src="{{ ($order->confirm == 'pending') ? asset('backend/img/warning.png') : asset('backend/img/correct.png') }}" alt=""></span>
                            <div class="payment-title">
                                <div class="text_1">
                                    <span class="isConfirm">{{ __('order.confirm')[$order->confirm] }}</span>
                                    {{ convert_price($order->cart['cartTotal'] - $order->promotion['discount'], true) }}₫
                                </div>
                                <div class="text_2">{{ array_column(__('payment.method'), 'title', 'name')[$order->method] ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="cancle-block">
                            
                            @if($order->confirm == 'confirm' && $order->payment != 'paid')
                                <button class="button updateField" data-field="confirm" data-value="cancle" data-title="ĐÃ HỦY THANH TOÁN ĐƠN HÀNG">Hủy đơn</button>
                            @elseif($order->payment == 'paid')
                                Đơn hàng đã thanh toán
                            @elseif($order->confirm == 'cancle')
                                Đơn hàng đã hủy
                            @endif


                        </div>
                    </div>
                </div>
                <div class="payment-confirm">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="uk-flex uk-flex-middle">
                            <span class="icon"><i class="fa fa-truck"></i></span>
                            <div class="payment-title">
                                <div class="text_1">Xác nhận đơn hàng</div>
                            </div>
                        </div>
                        <div class="confirm-block">
                            @if($order->confirm == 'pending')
                                <button class="button confirm updateField" data-field="confirm" data-value="confirm" data-title="ĐÃ XÁC NHẬN ĐƠN HÀNG TRỊ GIÁ">Xác nhận</button>
                            @else
                                Đã xác nhận
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 order-aside">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <span>Ghi chú</span>
                        <div class="edit span edit-order" data-target="description">Sửa</div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="description">
                        {{ $order->description }}
                    </div>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h5>Thông tin khách hàng</h5>
                        <div class="edit span edit-order" data-target="customerInfo">Sửa</div>
                    </div>
                </div>
                <div class="ibox-content order-customer-information">
                    <div class="customer-line">
                        <strong>N:</strong>
                        <span class="fullname">{{ $order->fullname }}</span>
                    </div>
                    <div class="customer-line">
                        <strong>E:</strong>
                        <span class="email">{{ $order->email }}</span>
                    </div>
                    <div class="customer-line">
                        <strong>P:</strong>
                        <span class="phone">{{ $order->phone }}</span>
                    </div>
                    <div class="customer-line">
                        <strong>A:</strong>
                        <span class="address">{{ $order->address }}</span>
                    </div>
                    <div class="customer-line">
                        <strong>P:</strong>
                        {{ $order->ward_name }}
                        
                    </div>
                    <div class="customer-line">
                        <strong>Q:</strong>
                        {{ $order->district_name }}
                        
                    </div>
                    <div class="customer-line">
                        <strong>T:</strong>
                        {{ $order->province_name }}
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="orderId" value="{{ $order->id }}">
<input type="hidden" class="ward_id" value="{{ $order->ward_id }}">
<input type="hidden" class="district_id" value="{{ $order->district_id }}">
<input type="hidden" class="province_id" value="{{ $order->province_id }}">
<script>
    var provinces = @json($provinces->map(function($item){
        return [
            'id' => $item->code,
            'name' => $item->name
        ];
    })->values());
</script>