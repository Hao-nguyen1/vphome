<form action="{{ route('construction.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perpage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @php
                        $publish = request('publish') ?: old('publish');
                    @endphp
                    <select name="publish" class="form-control setupSelect2 ml10">
                        @foreach(config('apps.general.publish') as $key => $val)
                        <option {{ ($publish == $key)  ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                    <div class="uk-search uk-flex uk-flex-middle mr10">
                        <div class="input-group">
                            <input 
                                type="text" 
                                name="keyword" 
                                value="{{ request('keyword') ?: old('keyword') }}" 
                                placeholder="Nhập Từ khóa bạn muốn tìm kiếm..." class="form-control"
                            >
                           <span class="input-group-btn">
                               <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm">Tìm Kiếm
                                </button>
                           </span>
                        </div>
                    </div>
                    <a href="{{ route('construction.create') }}" class="btn btn-danger"><i class="fa fa-plus mr5"></i>Thêm mới công trình</a>
                </div>
            </div>
        </div>
    </div>
</form>