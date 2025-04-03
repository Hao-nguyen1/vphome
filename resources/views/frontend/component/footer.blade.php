<div class="panel-subcribe">
    <div class="uk-container uk-container-center">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="panel-head">
                <h2 class="heading-3"><span>Đăng ký nhận thông tin</span></h2>
                <div class="description">Để nhận những thông tin khuyến mãi, chiết khấu hấp dẫn</div>
            </div>
            <div class="panel-body">
                <div class="uk-flex uk-flex-middle">    
                    <form action="" class="uk-form subcribe-form">
                        <input type="text" name="phone" value="" placeholder="Nhập vào số điện thoại">
                        <button type="submit" name="submit" value="submit">Đăng ký</button>
                    </form>
                    <div class="social">
                        <div class="uk-flex uk-flex-middle">
                            <a href="{{ $system['social_facebook'] }}" class="social-item"><i class="fa fa-facebook"></i></a>
                            <a href="{{ $system['social_instagram'] }}" class="social-item"><i class="fa fa-instagram"></i></a>
                            <a href="{{ $system['social_youtube'] }}" class="social-item"><i class="fa fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="upper">
        <div class="uk-container uk-container-center">
            <div class="footer-information">
                <div class="footer-logo"><img src="{{ $system['homepage_logo'] }}" alt=""></div>
                <div class="company-name">{{ $system['homepage_company'] }}</div>
                @if(isset($menu['footer-menu']))
                <div class="uk-grid uk-grid-medium">
                   @foreach($menu['footer-menu'] as $key => $val)
                   @php
                       $name = $val['item']->languages->first()->pivot->name;
                   @endphp
                    <div class="uk-width-large-1-4">
                        <div class="footer-menu">
                            <div class="ft-heading">{{ $name }}</div>
                            @if(count($val['children']))
                            <ul class="uk-list uk-clearfix">
                                @foreach($val['children'] as $item)
                                @php
                                    $name = $item['item']->languages->first()->pivot->name;
                                    $canonical = write_url($item['item']->languages->first()->pivot->canonical);
                                @endphp
                                <li><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    <div class="uk-width-large-1-4">
                        <div class="footer-contact">
                            <div class="ft-heading">Thông tin liên hệ</div>
                            <p>Địa chỉ: {{ $system['contact_address'] }}</p>
                            <p>Số điện thoại: {{ $system['contact_hotline'] }}</p>
                            <p>Email: {{ $system['contact_website'] }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="copyright uk-text-center">
        © Copyright 2024, All Rights Reserved - Design by:  {{ $system['homepage_brand'] }}
    </div>
</footer>
<div class="bottom-support-online">
    <div class="support-content">
        <a href="tel:0905620486" class="phone-call-now" rel="nofollow">
            <i style="background:#d92329" class="fa fa-phone rotate" aria-hidden="true"></i>
            <div class="animated infinite zoomIn kenit-alo-circle" style="border-color:#d92329"></div>
            <div class="animated infinite pulse kenit-alo-circle-fill" style="background-color:#d92329"></div>
            <span style="background:#d92329">Gọi ngay: {{ $system['contact_hotline'] }}</span>
        </a>
        <a class="mes" href="https://zalo.me/{{ $system['contact_hotline'] }}" target="_blank">
            <i style="background:#d92329" class="fa fa-comments"></i>
            <span style="background:#d92329">Chat qua Zalo</span>
        </a>
    </div>
    <a class="btn-support">
        <i style="background:#d92329" class="fa fa-bell" aria-hidden="true"></i>
        <div class="animated infinite zoomIn kenit-alo-circle" style="border-color:#d92329"></div>
        <div class="animated infinite pulse kenit-alo-circle-fill" style="background-color:#d92329"></div>
    </a>
</div>
<div class="fix-bottom uk-position-fixed uk-hidden-large">
    <div class="uk-grid uk-grid-collapse">
        <div class="uk-width-2-4">
            <div class="fix-item">
                <a href="tel:{{ $system['contact_hotline'] }}" class="btn btn-main" target="_blank">
                    <div class="icon"><i class="fa fa-phone rotate"></i></div>
                    <div class="text">Gọi điện</div>
                </a>
            </div>
        </div>
        <!-- <div class="uk-width-1-4">
            <div class="fix-item">
                <a href="" class="btn btn-main" target="_blank">
                    <div class="icon"><i class="fa fa-comments"></i></div>
                    <div class="text">Nhắn tin</div>
                </a>
            </div>
        </div> -->
        <div class="uk-width-2-4">
             <div class="fix-item">
                <a href="https://zalo.me/{{ $system['contact_hotline'] }}" class="btn btn-main" target="_blank">
                    <div class="image img-cover"><i class="fa fa-comments"></i></div>
                    <div class="text">Zalo</div>
                </a>
            </div>
        </div>
        <!-- <div class="uk-width-1-4">
            <div class="fix-item">
                <a href="{{ $system['social_facebook'] }}" class="btn btn-main" target="_blank">
                    <div class="image img-cover"><i class="fa fa-facebook"></i></div>
                    <div class="text" style="word-break: break-all;">Facebook</div>
                </a>
            </div>
        </div> -->
    </div>
</div>

<div class="noti" id="noti" style="bottom:-80px;">
   
</div>