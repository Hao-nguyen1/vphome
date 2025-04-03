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
                            <h2 class="heading-2"><span>Danh sách khách hàng</span></h2>
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <div class="description">
                                    Quản lý thông tin chi tiết danh sách các khách hàng tại {{ $system['homepage_brand'] }}
                                </div>
                                <form action="{{ route('agency.customer') }}" class="uk-form form search-form">
                                    <div class="uk-flex uk-flex-middle">
                                        <div class="input-group mr5">
                                            <input 
                                                type="text" 
                                                name="keyword" 
                                                value="{{ request('keyword') ?: old('keyword') }}" 
                                                placeholder="Nhập từ khóa bạn muốn tìm kiếm...." class="form-control"
                                            >
                                            <span class="input-group-btn">
                                                <button type="submit" name="search" value="search" class="btn-search mb0 btn-sm">Tìm kiếm
                                                </button>
                                            </span>
                                        </div>
                                        <a href="{{ route('agency.customer.create')}}" class="btn-create">
                                            <i class="fa fa-plus mr5"></i>
                                            Thêm mới khách hàng
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="uk-table uk-table-hover uk-table-striped uk-table-condensed construction-table table100 ver1 m-b-110">
                                <thead>
                                    <tr>
                                        <th>Mã KH</th>
                                        <th>Tên khách hàng</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th class="text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!is_null($customers))
                                        @foreach($customers as $key => $val)
                                            <tr>
                                                <td>{{ $val->code }}</td>
                                                <td>{{ $val->name }}</td>
                                                <td>{{ $val->email }}</td>
                                                <td>{{ $val->phone }}</td>
                                                <td>{{ $val->address }}</td>
                                                <td class="text-center"> 
                                                    <a href="{{ route('agency.construction.edit', $val->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                                    <a href="{{ route('agency.construction.delete') }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8" class="text-danger">Bạn chưa có công trình nào</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            {{  $customers->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



