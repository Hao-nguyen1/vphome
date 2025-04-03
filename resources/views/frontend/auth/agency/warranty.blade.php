@extends('frontend.homepage.layout')
@section('content')
    <div class="profile-container pt20 pb20">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-5">
                    @include('frontend.auth.agency.components.sidebar')
                </div>
                <div class="uk-width-large-4-5">
                    <div class="panel-profile">
                        <div class="panel-head">
                            <h2 class="heading-2"><span>Danh sách bảo hành của công trình</span></h2>
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <div class="description">
                                    Quản lý thông tin chi tiết bảo hành sản phẩm của công trình tại {{ $system['homepage_brand'] }}
                                </div>
                                <form action="{{ route('agency.check.warranty') }}" class="uk-form form search-form">
                                    <div class="uk-flex uk-flex-middle">
                                            @php
                                                $status = request('status') ?: old('status');
                                            @endphp
                                            <select name="status" class="form-control">
                                                <option value="">Tất cả</option>
                                                <option value="active" {{ ($status == 'active') ? 'selected' : '' }}>Đã kích hoạt</option>
                                                <option value="pending" {{ ($status == 'pending') ? 'selected' : '' }}>Kích hoạt bảo hành</option>
                                            </select>
                                            <div class="input-group">
                                                <input 
                                                    type="text" 
                                                    name="keyword" 
                                                    value="{{ request('keyword') ?: old('keyword') }}" 
                                                    placeholder="Nhập từ khóa bạn muốn tìm kiếm...." class="form-control"
                                                >
                                            <span class="input-group-btn">
                                                <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm">Tìm kiếm
                                                </button>
                                            </span>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="uk-table uk-table-hover uk-table-striped uk-table-condensed construction-table table100 ver1 m-b-110">
                                <thead>
                                    <tr>
                                        <th>Mã công trình</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Hệ màu</th>
                                        <th>Số lượng</th>
                                        <th>Bảo hành</th>
                                        <th>Bắt đầu</th>
                                        <th>Kết thúc</th>
                                        <th>Tình trạng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!is_null($warranty))
                                        @foreach($warranty as $key => $val)
                                        @php
                                            $status = ($val->status === 'active') ? 'Đã kích hoạt' : '<span class="text-danger">Kích hoạt bảo hành</span>';
                                        @endphp
                                        <tr>
                                            <td>{{ $val->code }}</td>
                                            <td>{{ $val->name }}</td>
                                            <td>{{ $val->color }}</td>
                                            <td>{{ $val->quantity }}</td>
                                            <td>{{ $val->warranty }} tháng</td>
                                            <td class="uk-text-center startDate">
                                                {{ $val->status === 'active' ? \Carbon\Carbon::parse($val->startDate)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="uk-text-center endDate">
                                                {{ $val->status === 'active' ? \Carbon\Carbon::parse($val->endDate)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td ><a href="" class="{{ ($val->status == 'pending') ? 'active-warranty' : '' }}" data-warranty="{{ $val->warranty }}" data-product-id="{{ $val->product_id  }}" data-construction-id="{{ $val->id }}" >{!! $status !!}</a></td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8" class="text-danger">Bạn chưa có công trình nào</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



