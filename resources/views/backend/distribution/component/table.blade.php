<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>Tên nhà phân phối</th>
        <th>Số điện thoại</th>
        <th>Email</th>
        <th>Địa chỉ</th>
        <th class="text-center">Tình Trạng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($distributions) && is_object($distributions))
            @foreach($distributions as $distribution)
            <tr >
                <td>
                    <input type="checkbox" value="{{ $distribution->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    {{ $distribution->name }}
                </td>
                <td>
                    {{ $distribution->phone }}
                </td>
                <td>
                    {{ $distribution->email }}
                </td>
                <td>
                    {{ $distribution->address }}
                </td>
                
                <td class="text-center js-switch-{{ $distribution->id }}"> 
                    <input type="checkbox" value="{{ $distribution->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ ($distribution->publish == 2) ? 'checked' : '' }} data-modelId="{{ $distribution->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('distribution.edit', $distribution->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('distribution.delete', $distribution->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $distributions->links('pagination::bootstrap-4') }}
