@extends('frontend.homepage.layout')
@section('content')
    <div class="profile-container pt20 pb20">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-5">
                    @include('frontend.auth.customer.components.sidebar')
                </div>
                <div class="uk-width-large-4-5">
                    <div class="panel-profile">
                        <div class="panel-head">
                            <h2 class="heading-2"><span>Danh sách công trình</span></h2>
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <div class="description">
                                    Quản lý thông tin chi tiết danh sách các công trình tại {{ $system['homepage_brand'] }}
                                </div>
                                <form action="{{ route('customer.construction') }}" class="uk-form form search-form">
                                    <div class="uk-flex uk-flex-middle">
                                            @php
                                                $confirm = request('confirm') ?: old('confirm');
                                            @endphp
                                            <select name="confirm" class="form-control">
                                                <option value="">Tất cả</option>
                                                <option value="confirmed" {{ ($confirm == 'confirmed') ? 'selected' : '' }}>Đã xác nhận</option>
                                                <option value="pending" {{ ($confirm == 'pending') ? 'selected' : '' }}>Chờ xác nhận</option>
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
                                        <th>Mã CT</th>
                                        <th>Tên công trình</th>
                                        <th>Mã Đại lý</th>
                                        <th>Địa chỉ</th>
                                        <th>Xưởng</th>
                                        <th>Chủ đầu tư</th>
                                        <th>Điểm</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!is_null($constructs))
                                        @foreach($constructs as $key => $val)
                                        @php
                                            $confirm = ($val->confirm == 'confirmed') ? '<span class="text-success" >Đã xác nhận</span>' : '<span class="text-danger">Chờ xác nhận</span>';
                                        @endphp
                                        <tr>
                                            <td><a href="{{ route('customer.construction.product', ['id' => $val->id]) }}">{{ $val->code }}</a></td>
                                            <td>{{ $val->name }}</td>
                                            <td><a href="#agency-popup" class="showAgency"  data-agency="{{ json_encode($val->agencys) }}">{{ $val->agencys->code }}</a></td>
                                            <td>{{ $val->address }}</td>
                                            <td>{{ $val->workshop }}</td>
                                            <td>{{ $val->invester }}</td>
                                            <td>{{ $val->point }}</td>
                                            <td>{!! $confirm !!}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8" class="text-danger">Bạn chưa có công trình nào</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            {{  $constructs->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="agency-popup" class="uk-modal">
        <div class="uk-modal-dialog">
            <a class="uk-modal-close uk-close"></a>
            <div class="agency-popup-container">
                <h2 class="heading-1 mb30">Thông tin đại lý</h2>
                <div class="agency-popup-content">
                    <p><strong>Tên đại lý: </strong> <span class="popup-agency-name"></span> </p>
                    <p><strong>Mã đại lý: </strong> <span class="popup-agency-code"></span> </p>
                    <p><strong>Email: </strong> <span class="popup-agency-email"></span> </p>
                    <p><strong>Số điện thoại: </strong> <span class="popup-agency-phone"></span> </p>
                    <p><strong>Địa chỉ: </strong> <span class="popup-agency-address"></span> </p>
                </div>
            </div>
        </div>
    </div>

@endsection



