@if ($momo['m2signature'] == $momo['partnerSignature']) 
    <div class="alert alert-success"><strong>Tình trạng thanh toán: </strong>Thành công</div>
@else 
<div class="alert alert-danger">Giao dịch thanh toán online không thành công. Vui lòng liên hệ: {{ $system['contact_hotline'] }}</div>
@endif