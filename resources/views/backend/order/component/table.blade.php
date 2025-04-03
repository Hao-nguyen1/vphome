<div class="mb10"><div class="text-danger" style="font-size:12px;"><i>*Tổng cuối là tổng chưa bao gồm giảm giá</i></div></div>
<table class="table table-striped table-bordered order-table">
    <thead>
    <tr>
        <th>
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>Mã</th>
        <th>Ngày tạo</th>
        <th>Khách hàng</th>
        <th class="text-right">Giảm giá (vnđ)</th>
        {{-- <th class="text-right">Phí ship (vnđ)</th> --}}
        <th class="text-right">Tổng cuối (vnđ)</th>
        <th class="text-center">Trạng thái</th>
        <th>Thanh toán</th>
        <th>Giao hàng</th>
        <th>Hình thức</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($orders) && is_object($orders))
            @foreach($orders as $order)
            <tr >
                <td>
                    <input type="checkbox" value="{{ $order->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    <a href="{{ route('order.detail', $order->id) }}">{{ $order->code }}</a>
                </td>
                <td>
                    {{ convertDateTime($order->created_at, 'd-m-Y') }}
                </td>
                <td>
                    <div><b>N:</b> {{ $order->fullname }}</div>
                    <div><b>P:</b> {{ $order->phone }}</div>
                    <div><b>A:</b> {{ $order->address }}</div>
                </td>
                
                <td class="text-right order-discount">
                    {{ convert_price($order->promotion['discount'], true) }}
                </td>
                {{-- <td class="text-right order-shipping">
                    {{ convert_price($order->shipping, true) }}
                </td> --}}
                <td class="text-right order-total">
                    {{ convert_price($order->cart['cartTotal'], true) }}
                </td>
                <td class="text-center">
                    {!! ($order->confirm != 'cancle') ? __('cart.confirm')[$order->confirm] : '<span class="cancle-badge">'.__('cart.confirm')[$order->confirm].'</span>' !!}
                </td>
                @foreach(__('cart') as $keyItem => $item)
                @if($keyItem === 'confirm') @continue @endif
                <td class="text-center">
                    @if($order->confirm != 'cancle')
                    <select name="{{ $keyItem }}"  class="setupSelect2 updateBadge" data-field="{{ $keyItem }}">
                        @foreach($item as $keyOption => $option)
                        @if($keyOption === 'none') @continue @endif
                        <option {{ ($keyOption == $order->{$keyItem}) ? 'selected' : '' }} value="{{ $keyOption }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    @else
                    -
                    @endif
                    <input type="hidden" class="changeOrderStatus" value="{{ $order->{$keyItem} }}">
                </td>
                @endforeach
                <td class="text-center">
                    @if($order->confirm != 'cancle')
                        <img title="{{ array_column(__('payment.method'), 'title', 'name')[$order->method] ?? '-' }}" style="max-width:54px;" src="{{ array_column(__('payment.method'), 'image', 'name')[$order->method] ?? '-' }}" alt="">
                    @else
                    -
                    @endif
                    
                    <input type="hidden" class="confirm" value="{{ $order->confirm }}">
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $orders->links('pagination::bootstrap-4') }}
