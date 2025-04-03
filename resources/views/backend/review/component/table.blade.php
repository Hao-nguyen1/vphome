<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>Họ Tên</th>
        <th>Số điện thoại</th>
        <th>Email</th>
        <th style="width: 400px;">Nội dung</th>
        <th>Rate</th>
        <th>Đối tượng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($reviews) && is_object($reviews))
            @foreach($reviews as $review)
            @php
                $reviewableLink = $review->reviewable->languages->first()->pivot->canonical;
            @endphp
            <tr >
                <td>
                    <input type="checkbox" value="{{ $review->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    {{ $review->fullname }}
                </td>
                <td>
                    {{ $review->phone }}
                </td>
                <td>
                    {{ $review->email }}
                </td>
                <td>
                    {{ $review->description }}
                </td>
                <td class="text-center">
                    <div class="text-navy">{{ $review->score }}</div>
                </td>
                <td>
                    <a href="{{ write_url($reviewableLink) }}" target="_blank">Click để xem đối tượng</a>
                </td>
                <td class="text-center"> 
                    <a href="{{ route('review.delete', $review->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $reviews->links('pagination::bootstrap-4') }}
