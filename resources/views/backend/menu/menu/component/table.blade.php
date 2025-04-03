<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>Tên Menu</th>
        <th>Từ khóa</th>
        <th class="text-center">Tình Trạng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($menuCatalogues) && is_object($menuCatalogues))
            @foreach($menuCatalogues as $menuCatalogue)
            <tr >
                <td>
                    <input type="checkbox" value="{{ $menuCatalogue->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    {{ $menuCatalogue->name }}
                </td>
                <td>
                    {{ $menuCatalogue->keyword }}
                </td>
               
                <td class="text-center js-switch-{{ $menuCatalogue->id }}"> 
                    <input type="checkbox" value="{{ $menuCatalogue->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ ($menuCatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $menuCatalogue->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('menu.edit', $menuCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('menu.delete', $menuCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
