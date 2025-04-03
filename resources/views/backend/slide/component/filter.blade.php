<form action="{{ route('slide.index') }}">
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
                    <a href="{{ route('slide.create') }}" class="btn btn-danger"><i class="fa fa-plus mr5"></i>Thêm mới slide</a>
                </div>
            </div>
        </div>
    </div>
</form>