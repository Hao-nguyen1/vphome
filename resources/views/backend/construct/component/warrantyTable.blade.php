<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Mã công trình</th>
        <th>Khách hàng</th>
        <th>Đại lý</th>
        <th>Chủ đầu tư</th>
        <th class="text-left">Sản phẩm</th>
        <th class="text-left">Thời gian dự kiến</th>
        <th class="text-center">Xác nhận</th>
        {{-- <th class="text-center">Tình Trạng</th> --}}
    </tr>
    </thead>
    <tbody>
        @if(isset($warranty) && is_object($warranty))
            @foreach($warranty as $val)
                @if($val->status == 'active')
                    <tr>
                        <td>
                            <div class="text-small text-success">{{ $val->code }}</div> 
                        </td>
                        <td>
                            <div>{{ $val->customers->name }}</div> 
                            <div class="text-small text-success">Số ĐT: {{ $val->customers->phone }}</div> 
                        </td>
                        <td>
                            <div class="text-small text-success">{{ $val->agencys->code }}</div> 
                        </td>
                        <td>
                            {{ $val->invester }}
                        </td>
                        <td>
                            {{ $val->product_name }}
                        </td>
                        <td>
                            {{\Carbon\Carbon::parse($val->startDate)->format('d-m-Y') }} --- {{ \Carbon\Carbon::parse($val->endDate)->format('d-m-Y') }}
                        </td>
                        <td class="text-center">
                            {!! ($val->status == 'active') ? '<span class="text-navy">Đã kích hoạt</span>'  : '' !!}
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>
{{  $warranty->links('pagination::bootstrap-4') }}
