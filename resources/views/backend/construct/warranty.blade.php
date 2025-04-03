@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['index']['title']])
<div class="row mt20">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <h5>Danh sách yêu cầu kích hoạt bảo hành</h5>
                    <form action="{{ route('construction.warranty') }}">
                        <div class="filter-wrapper">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <div class="action">
                                        <div class="uk-flex uk-flex-middle">
                                            @php
                                                $status = request('status') ?: old('status');
                                            @endphp
                                        <select name="status" class="form-control setupSelect2">
                                            <option value="">Tất cả</option>
                                            <option value="active" {{ ($status == 'active') ? 'selected' : '' }}>Đã kích hoạt</option>
                                            <option value="pending" {{ ($status == 'pending') ? 'selected' : '' }}>Kích hoạt bảo hành</option>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="ibox-content">
                @include('backend.construct.component.warrantyTable')
            </div>
        </div>
    </div>
</div>

