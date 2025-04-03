<form action="{{ route('customer.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="perpage">
                @php
                    $perpage = request('perpage') ?: old('perpage');
                @endphp
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <select name="perpage" class="form-control input-sm perpage filter mr10">
                        @for($i = 20; $i<= 200; $i+=20)
                        <option {{ ($perpage == $i)  ? 'selected' : '' }}  value="{{ $i }}">{{ $i }} bản ghi</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                   @include('backend.dashboard.component.filterPublish')
                    <select name="customer_catalogue_id" class="form-control mr10 setupSelect2">
                        <option value="0" selected="selected">Chọn Nhóm Thành Viên</option>
                        @foreach($customerCatalogues as $key => $val)
                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                        @endforeach
                    </select>
                    @include('backend.dashboard.component.keyword')
                    <a href="{{ route('customer.create') }}" class="btn btn-danger"><i class="fa fa-plus mr5"></i>Thêm mới thành viên</a>
                </div>
            </div>
        </div>
    </div>
</form>