@php
    $query= base64_encode(http_build_query(request()->query()));
    $queryUrl = rtrim($query,'=');
@endphp
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width:50px;">
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>{{ __('messages.tableName') }}</th>
        @include('backend.dashboard.component.languageTh')
        <th class="text-center" style="width:100px;">{{ __('messages.tableStatus') }} </th>
        <th class="text-center" style="width:100px;">{{ __('messages.tableAction') }} </th>
    </tr>
    </thead>
    <tbody>
        @if(isset($attributeCatalogues) && is_object($attributeCatalogues))
            @foreach($attributeCatalogues as $attributeCatalogue)
            <tr >
                <td>
                    <input type="checkbox" value="{{ $attributeCatalogue->id }}" class="input-checkbox checkBoxItem">
                </td>
               
                <td>
                    {{ str_repeat('|----', (($attributeCatalogue->level > 0)?($attributeCatalogue->level - 1):0)).$attributeCatalogue->name }}
                </td>
                @include('backend.dashboard.component.languageTd', ['model' => $attributeCatalogue, 'modeling' => 'AttributeCatalogue'])
                <td class="text-center js-switch-{{ $attributeCatalogue->id }}"> 
                    <input type="checkbox" value="{{ $attributeCatalogue->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ ($attributeCatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $attributeCatalogue->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('attribute.catalogue.edit', [$attributeCatalogue->id, $queryUrl ?? '']) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('attribute.catalogue.delete', $attributeCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $attributeCatalogues->links('pagination::bootstrap-4') }}
