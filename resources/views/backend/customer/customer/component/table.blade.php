<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>Họ Tên</th>
        <th>Email</th>
        <th>Số điện thoại</th>
        <th>Địa chỉ</th>
        <th class="text-center">Nhóm khách hàng</th>
        <th class="text-center">Nguồn</th>
        <th class="text-center">Tình Trạng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($customers) && is_object($customers))
            @foreach($customers as $customer)
            <tr >
                <td>
                    <input type="checkbox" value="{{ $customer->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    {{ $customer->name }}
                </td>
                <td>
                    {{ $customer->email }}
                </td>
                <td>
                    {{ $customer->phone }}
                </td>
                <td>
                    {{ $customer->address }}
                </td>
                <td class="text-center">
                    {{ $customer->customer_catalogues->name }}
                </td>
                <td class="text-center">
                    {{ $customer->sources->name }}
                </td>
                <td class="text-center js-switch-{{ $customer->id }}"> 
                    <input type="checkbox" value="{{ $customer->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ ($customer->publish == 2) ? 'checked' : '' }} data-modelId="{{ $customer->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('customer.delete', $customer->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $customers->links('pagination::bootstrap-4') }}
