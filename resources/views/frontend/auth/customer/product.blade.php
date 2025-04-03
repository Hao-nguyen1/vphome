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
                            <h2 class="heading-2"><span>Danh sách sản phẩm của công trình {{ $construction->nane }}</span></h2>
                            <div class="description">
                                Quản lý thông tin chi tiết danh sách sản phẩm của công trình tại {{ $system['homepage_brand'] }}
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="uk-table uk-table-hover uk-table-striped uk-table-condensed construction-table table100 ver1 m-b-110">
                                <thead>
                                    <tr>
                                        <th>Mã Sản phẩm</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Hệ màu</th>
                                        <th>Số lượng</th>
                                        <th>Bảo hành</th>
                                        <th>Tình trạng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!is_null($construction->products))
                                        @foreach($construction->products as $key => $val)
                                        @php
                                            $active = ($val->confirm == 'active') ? '<span class="text-success">Đã kích hoạt</span>' : '<span class="text-danger">Chưa kích hoạt</span>';
                                            $name = $val->languages->first()->pivot->name;
                                        @endphp
                                        <tr>
                                            <td>{{ $val->code }}</td>
                                            <td>{{ $name }}</td>
                                            <td>{{ $val->pivot->color }}</td>
                                            <td>{{ $val->pivot->quantity }}</td>
                                            <td>{{ $val->pivot->warranty }} tháng</td>
                                            <td>{!! $active !!}</td>
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



