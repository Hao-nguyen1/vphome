@extends('frontend.homepage.layout')
@section('content')
    <div class="cart-success">
        <div class="panel-head">
            <h2 class="cart-heading"><span>Đặt hàng thành công</span></h2>
            <div class="discover-text"><a href="{{ write_url('san-pham') }}">Khám phá thêm các sản phẩm khác tại đây</a></div>
        </div>
        <div class="panel-body">
            <h2 class="cart-heading"><span>Thông tin đơn hàng</span></h2>
            <div class="checkout-box">
                <div class="checkout-box-head">
                    <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
                        <div class="uk-width-large-1-3"></div>
                        <div class="uk-width-large-1-3">
                            <div class="order-title uk-text-center">ĐƠN HÀNG #{{ $order->code }}</div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="order-date">{{ convertDateTime($order->created_at); }}</div>
                        </div>
                    </div>
                </div>
                <div class="checkout-box-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="uk-text-left">Tên sản phẩm</th>
                                <th class="uk-text-center">Số lượng</th>
                                <th class="uk-text-right">Giá niêm yết</th>
                                <th class="uk-text-right">Giá bán</th>
                                <th class="uk-text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $carts = $order->products;
                            @endphp
                            @foreach($carts as $key => $val)
                            @php
                                $name = $val->pivot->name;
                                $qty = $val->pivot->qty;
                                $price = convert_price($val->pivot->price, true);
                                $priceOriginal = convert_price($val->pivot->priceOriginal, true);
                                $subtotal = convert_price($val->pivot->price * $qty, true);
                            @endphp
                            <tr>
                                <td class="uk-text-left">{{ $name }}</td>
                                <td class="uk-text-center">{{ $qty }}</td>
                                <td class="uk-text-right">{{ $priceOriginal }}đ</td>
                                <td class="uk-text-right">{{ $price }}đ</td>
                                <td class="uk-text-right"><strong>{{ $subtotal }}đ</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">Mã giảm giá</td>
                                <td><strong>{{ $order->promotion['code'] }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4">Tổng giá trị sản phẩm</td>
                                <td><strong>{{ convert_price($order->cart['cartTotal'], true) }}đ</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4">Tổng giá trị khuyến mãi</td>
                                <td><strong>{{ convert_price($order->promotion['discount'], true) }}đ</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4">Phí giao hàng</td>
                                <td><strong>0đ</strong></td>
                            </tr>
                            <tr class="total_payment">
                                <td colspan="4"><span>Tổng thanh toán</span></td>
                                <td>{{ convert_price($order->cart['cartTotal'] - $order->promotion['discount'], true) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel-foot">
            <h2 class="cart-heading"><span>Thông tin nhận hàng</span></h2>
            <div class="checkout-box">
                <div>Tên người nhận: {{ $order->fullname }}<span></span></div>
                <!-- <div>Email: {{ $order->email }}<span></span></div> -->
                <div>Địa chỉ: {{ $order->address }}<span></span></div>
                @php
                    $province = $order->provinces->first()->name;
                    $district = $order->provinces->first()->districts->where('code',$order->district_id)->first()->name;
                    $ward =$order->provinces->first()->districts->where('code',$order->district_id)->first()->wards->where('code',$order->ward_id)->first()->name;
                @endphp
                <div>Phường/Xã: <span>{{ $ward }}</span>
                </div>
                <div>Quận/Huyện: <span>{{ $district }}</span></div>
                <div>Tỉnh/Thành phố: <span>{{ $province }}</span></div>
                <div>Số điện thoại: {{ $order->phone }}<span></span></div>
                <div>Hình thức thanh toán: {{ array_column(__('payment.method'), 'title', 'name')[$order->method] ?? '-' }}<span></span></div>

                @if(isset($template))
                    @include($template)
                @endif

            </div>
        </div>
    </div>
@endsection


