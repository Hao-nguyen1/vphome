<div class="panel-foot mt30 pay">
    <div class="cart-summary mb20">
        <div class="cart-summary-item">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <span class="summay-title">Giảm giá</span>
                <div class="summary-value discount-value">-{{ convert_price($cartPromotion['discount'], true) }}đ</div>
            </div>
        </div>
        <div class="cart-summary-item">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <span class="summay-title">Phí giao hàng</span>
                <div class="summary-value">Miễn phí</div>
            </div>
        </div>
        <div class="cart-summary-item">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <span class="summay-title bold">Tổng tiền</span>
                <div class="summary-value cart-total">{{ (count($carts) && !is_null($carts) ) ? convert_price($cartCaculate['cartTotal'] - $cartPromotion['discount'], true) : 0   }}đ</div>
            </div>
        </div>
        <div class="buy-more">
            <a href="{{ write_url('san-pham') }}" class="btn-buymore">Chọn thêm sản phẩm khác</a>
        </div>
    </div>
</div>