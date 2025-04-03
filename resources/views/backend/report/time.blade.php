<form action="{{ route('report.time') }}" method="get">
    <div class="wrapper wrapper-content report-time">
        @include('backend.dashboard.component.statistic')
        <div class="row mb15">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Báo cáo doanh thu theo thời gian</div>
                    <div class="panel-description">
                        <p>Nhập thông tin chung của người sử dụng</p>
                        <p>Lưu ý: Những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <h2 class="heading-1 panel-title"><span>Chọn khoảng thời gian:</span></h2>
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-row mb15">
                                    <label for="" class="control-label text-left">Ngày bắt đầu <span class="text-danger"> (*)</span></label>
                                    <div class="form-date">
                                        <input 
                                            type="text" 
                                            name="startDate" 
                                            value="{{ request('startDate') ?: old('startDate') }}" 
                                            class="form-control datepickerReport" 
                                            placeholder="" 
                                            autocomplete="off"
                                        >
                                        <span><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>       
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row mb15">
                                    <label for="" class="control-label text-left">Ngày kết thúc <span class="text-danger"> (*)</span></label>
                                    <div class="form-date">
                                        <input 
                                            type="text" 
                                            name="endDate" 
                                            value="{{ request('endDate') ?: old('endDate') }}" 
                                            class="form-control datepickerReport"
                                            placeholder="" 
                                            autocomplete="off"
                                        >
                                        <span><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <button class="btn btn-success" type="submit" value="name">Gửi báo cáo</button>   
                            </div>
                        </div>
                    </div>
                </div>      
            </div>
        </div>
        <div class="row">
            <div class="ibox-content time">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-right">Ngày</th>
                            <th class="text-right">SL khách hàng</th>
                            <th class="text-right">SL đơn</th>
                            <th class="text-right">Tiền hàng(vnđ)</th>
                            <th class="text-right">Tổng chiết khấu(vnđ)</th>
                            <th class="text-right">Doanh thu(vnđ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($reports))
                            @php
                                $td = ['order_date', 'count_customer', 'count_order', 'sum_revenue|format', 'sum_discount|format'];
                            @endphp
                            @foreach($reports as $key => $val)
                                <tr class="table">
                                    @foreach($td as $item)
                                        @php
                                            $explode = explode('|', $item);
                                            $value = (isset($explode[1]) && $explode[1] == 'format' ) ? convert_price($val[$explode[0]], true) : $val[$explode[0]];
                                        @endphp
                                        <td class="text-right">
                                            {{ $value }}
                                        </td>
                                    @endforeach
                                    <td class="text-right">{{ convert_price($val['sum_revenue'] - $val['sum_discount'], true) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        {{-- {{  $users->links('pagination::bootstrap-4') }} --}}
    </div>
</form>