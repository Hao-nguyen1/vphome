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
        <th>Tên công trình</th>
        <th>Khách hàng</th>
        <th>Đại lý</th>
        <th>Chủ đầu tư</th>
        <th>Xưởng</th>
        <th class="text-right">Điểm tích lũy</th>
        <th class="text-center">Xác nhận</th>
        {{-- <th class="text-center">Tình Trạng</th> --}}
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($constructs) && is_object($constructs))
            @foreach($constructs as $construct)
            <tr >
                <td>
                    <input type="checkbox" value="{{ $construct->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    <div>{{ $construct->name }}</div> 
                    <div class="text-small text-success">Mã CT: {{ $construct->code }}</div> 
                </td>
                <td>
                    {{ $construct->customers->name }}
                </td>
                <td>
                    {{ $construct->agencys->code }}
                </td>
                <td>
                    {{ $construct->invester }}
                </td>
                <td>
                    {{ $construct->workshop }}
                </td>
                <td class="text-right">
                    {{ $construct->point }}
                </td>

                <td class="text-center">
                    {!! ($construct->confirm == 'confirmed') ? '<span class="text-navy">Đã xác nhận</span>' : '<span class="text-danger">Chưa xác nhận</span>' !!}
                </td>
                
                {{-- <td class="text-center js-switch-{{ $construct->id }}"> 
                    <input type="checkbox" value="{{ $construct->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ ($construct->publish == 2) ? 'checked' : '' }} data-modelId="{{ $construct->id }}" />
                </td> --}}
                <td class="text-center"> 
                    <a href="{{ route('construction.edit', [$construct->id, $queryUrl ?? '']) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('construction.delete', $construct->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $constructs->links('pagination::bootstrap-4') }}

