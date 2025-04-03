<?php   

    return [
        'model' => [
            'PostCatalogue' => 'Nhóm Bài Viết',
            'Post' => 'Bài Viết',
            'ProductCatalogue' => 'Danh mục',
            'Product' => 'Sản Phẩm',
        ],
        'type' => [
            'dropdown-menu' => 'Dropdown Menu',
            'mega-menu' => 'Mega Menu'
        ],
        'effect' => [
            'fade' => 'Fade',
            'cube' => 'Cube',
            'coverflow' => 'Coverflow',
            'flip' => 'Flip',
            'cards' => 'Cards',
            'creative' => 'Creative',
        ],
        'navigate' => [
            'hide' => 'Ẩn',
            'dots' => 'Dấu chấm',
            'thumbnails' => 'Ảnh Thumbnails'
        ],
        'promotion' => [
            'order_amount_range' => 'Chiết khấu theo tổng giá trị đơn hàng',
            'product_and_quantity' => 'Chiết khấu theo từng sản phẩm',
            'product_quantity_range' => 'Chiết khấu theo số lượng sản phẩm',
            'goods_discount_by_quantity' => 'Mua sản phẩm - giảm giá sản phẩm',
        ],
        'item' => [
            'Product' => 'Phiên bản sản phẩm',
            'ProductCatalogue' => 'Loại sản phẩm',
        ],
        'gender' => [
            [
                'id' => 1,
                'name' => 'Nam'
            ],
            [
                'id' => 2,
                'name' => 'Nữ'
            ],
        ],
        'day' => array_map(function($value){
            return ['id' => $value-1, 'name' => $value];
        }, range(1, 31)),
        'applyStatus' => [
            [
                'id' => 'staff_take_care_customer',
                'name' => 'Nhân viên phụ trách'
            ],
            [
                'id' => 'customer_group',
                'name' => 'Nhóm khách hàng'
            ],
            [
                'id' => 'customer_gender',
                'name' => 'Giới tính'
            ],
            [
                'id' => 'customer_birthday',
                'name' => 'Ngày sinh'
            ],
        ],
    ];

