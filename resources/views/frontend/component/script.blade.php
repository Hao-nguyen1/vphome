@php
    $coreScript = [
        'public/backend/js/plugins/toastr/toastr.min.js',
        'public/frontend/resources/plugins/wow/dist/wow.min.js',
        'public/frontend/resources/uikit/js/uikit.min.js',
        'public/frontend/resources/uikit/js/components/sticky.min.js',
        'public/frontend/resources/uikit/js/components/lightbox.min.js',
        'public/frontend/core/plugins/jquery-nice-select-1.1.0/js/jquery.nice-select.min.js',
        'public/frontend/resources/function.js'
    ];
    if(isset($config['js'])){
        foreach($config['js'] as $key => $val){
            array_push($coreScript, $val);
        }
    }
@endphp
@if(isset($config['externalJs']))
    @foreach ($config['externalJs'] as $item)
        <script src="{{ $item }}"></script>
    @endforeach
@endif
@foreach ($coreScript as $item)
    <script src="{{ asset($item) }}"></script>
@endforeach



<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v17.0&appId=103609027035330&autoLogAppEvents=1" nonce="E1aWx0Pa"></script>