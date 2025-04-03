@php
    $query= base64_encode(http_build_query(request()->query()));
    $queryUrl = rtrim($query,'=');
@endphp
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>Tên đại lý</th>
        <th>Email</th>
        <th>Số điện thoại</th>
        <th>Địa chỉ</th>
        <th class="text-center">Tình Trạng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($agencys) && is_object($agencys))
            @foreach($agencys as $agency)
            <tr >
                <td>
                    <input type="checkbox" value="{{ $agency->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    <div>{{ $agency->name }}</div> 
                    <div class="text-small text-success">Mã đại lý: {{ $agency->code }}</div> 
                </td>
                <td>
                    {{ $agency->email }}
                </td>
                <td>
                    {{ $agency->phone }}
                </td>
                <td>
                    {{ $agency->address }}
                </td>
                <td class="text-center js-switch-{{ $agency->id }}"> 
                    <input type="checkbox" value="{{ $agency->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ ($agency->publish == 2) ? 'checked' : '' }} data-modelId="{{ $agency->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('agency.edit', [$agency->id, $queryUrl ?? '']) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('agency.delete', $agency->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $agencys->links('pagination::bootstrap-4') }}

