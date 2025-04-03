<form action="{{ route('customer.catalogue.index') }}">
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
                    @include('backend.dashboard.component.keyword')
                    <div class="uk-flex uk-flex-middle">
                        {{-- <a href="{{ route('customer.catalogue.permission') }}" class="btn btn-warning mr10"><i class="fa fa-key mr5"></i>Phân Quyền</a> --}}
                        <a href="{{ route('customer.catalogue.create') }}" class="btn btn-danger"><i class="fa fa-plus mr5"></i>Thêm mới nhóm thành viên</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>