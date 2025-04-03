<div class="ibox">
    <div class="ibox-title">
        <h5>Danh sách đơn hàng mới</h5>
    </div>
    <div class="ibox-content">
        <div class="mb10"><div class="text-danger" style="font-size:12px;"><i>*Tổng cuối là tổng chưa bao gồm giảm giá</i></div></div>
        <table class="table table-striped table-bordered order-table">
            <thead>
            <tr>
                <th class="text-right">Mã</th>
                <th class="text-right">Ngày tạo</th>
                <th class="text-right">Khách hàng</th>
                <th class="text-right">Giảm giá (vnđ)</th>
                <th class="text-right">Tổng cuối (vnđ)</th>
            </tr>
            </thead>
            <tbody>
                @if(isset($newOrders) && is_object($newOrders))
                    @foreach($newOrders as $order)
                    <tr>
                        <td class="text-right">
                            <a href="{{ route('order.detail', $order->id) }}">{{ $order->code }}</a>
                        </td>
                        <td class="text-right">
                            {{ convertDateTime($order->created_at, 'd-m-Y') }}
                        </td>
                        <td class="text-right">
                            {{ $order->fullname }}
                        </td>
                        <td class="text-right order-discount">
                            {{ convert_price($order->promotion['discount'], true) }}
                        </td>
                        <td class="text-right order-total">
                            {{ convert_price($order->cart['cartTotal'], true) }}
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
