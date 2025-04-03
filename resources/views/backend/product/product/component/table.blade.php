<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width:50px;">
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th style="width:700px;">{{ __('messages.tableName') }}</th>
        @include('backend.dashboard.component.languageTh')
        <th style="width:80px;" class="text-center">{{ __('messages.tableQuantity') }}</th>
        <th class="text-center" style="width:100px;">{{ __('messages.tableStatus') }}</th>
        <th class="text-center" style="width:100px;">{{ __('messages.tableAction') }}</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($products) && is_object($products))
            @foreach($products as $product)
            <tr id="{{ $product->id }}">
                <td>
                    <input type="checkbox" value="{{ $product->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    <div class="uk-flex uk-flex-middle">
                        <div class="image mr5">
                            <div class="img-scaledown image-product"><img src="{{ image($product->image) }}" alt=""></div>
                        </div>
                        <div class="main-info">
                            <div class="name"><span class="maintitle">{{ $product->name }}</span></div>
                            <div class="catalogue">
                                <span class="text-danger">{{ __('messages.tableGroup') }} </span>
                                @foreach($product->product_catalogues as $val)
                                @foreach($val->product_catalogue_language as $cat)
                                <a href="{{ route('product.index', ['product_catalogue_id' => $val->id]) }}" title="">{{ $cat->name }}</a>
                                @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </td>
                @include('backend.dashboard.component.languageTd', ['model' => $product, 'modeling' => 'Product'])
                <td>
                    <input type="text" name="quantity" value="{{ $product->quantity }}" class="form-control text-right" readonly>
                </td>
                <td class="text-center js-switch-{{ $product->id }}"> 
                    <input type="checkbox" value="{{ $product->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ ($product->publish == 2) ? 'checked' : '' }} data-modelId="{{ $product->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('product.edit', [$product->id, $queryUrl ?? 'p']) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('product.delete', $product->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $products->links('pagination::bootstrap-4') }}