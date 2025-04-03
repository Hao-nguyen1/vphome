<?php   
return [
    'module' => [
        [
            'title' => 'Dashboard',
            'icon' => 'fa fa-database',
            'name' => ['dashboard'],
            'route' => 'dashboard/index',
            'class' => 'special'
        ],
        [
            'title' => 'Báo cáo doanh thu',
            'icon' => 'fa fa-money',
            'name' => ['report'],
            'subModule' => [
                [
                    'title' => 'Theo thời gian',
                    'route' => 'report/time'
                ],
                [
                    'title' => 'Theo sản phẩm',
                    'route' => 'report/product'
                ],
                [
                    'title' => 'Theo nguồn khách',
                    'route' => 'report/customer'
                ],
            ]
        ],
        // [
        //     'title' => 'CRM',
        //     'icon' => 'fa fa-instagram',
        //     'name' => ['construction','agency'],
        //     'subModule' => [
        //         [
        //             'title' => 'QL Đại lý',
        //             'route' => 'agency/index'
        //         ],
        //         [
        //             'title' => 'QL Công trình',
        //             'route' => 'construction/index'
        //         ],
        //         [
        //             'title' => 'QL Kích hoạt bảo hành',
        //             'route' => 'construction/warranty'
        //         ],
        //     ]
        // ],
        [
            'title' => 'QL Sản Phẩm',
            'icon' => 'fa fa-cube',
            'name' => ['product','attribute'],
            'subModule' => [
                [
                    'title' => 'QL Danh mục',
                    'route' => 'product/catalogue/index'
                ],
                [
                    'title' => 'QL Sản phẩm',
                    'route' => 'product/index'
                ],
                [
                    'title' => 'QL Loại thuộc tính',
                    'route' => 'attribute/catalogue/index'
                ],
                [
                    'title' => 'QL thuộc tính',
                    'route' => 'attribute/index'
                ],

            ]
        ],
        [
            'title' => 'QL đơn hàng',
            'icon' => 'fa fa-shopping-bag',
            'name' => ['order'],
            'subModule' => [
                [
                    'title' => 'QL Đơn Hàng',
                    'route' => 'order/index'
                ],
            ]
        ],
        [
            'title' => 'QL Nhóm Khách hàng',
            'icon' => 'fa fa-user',
            'name' => ['customer'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm Khách hàng',
                    'route' => asset('customer/catalogue/index')
                ],
                [
                    'title' => 'QL Khách hàng',
                    'route' => 'customer/index'
                ],
            ]
        ],
        [
            'title' => 'QL Marketing',
            'icon' => 'fa fa-money',
            'name' => ['promotion', 'source'],
            'subModule' => [
                [
                    'title' => 'QL Khuyến mại',
                    'route' => 'promotion/index'
                ],
                [
                    'title' => 'QL nguồn khách',
                    'route' => 'source/index'
                ],
            ]
        ],
        // [
        //     'title' => 'Hệ thống phân phối',
        //     'icon' => 'fa fa-truck',
        //     'name' => ['distribution'],
        //     'route' => 'distribution/index'
        // ],
        [
            'title' => 'QL Bài viết',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm Bài Viết',
                    'route' => 'post/catalogue/index'
                ],
                [
                    'title' => 'QL Bài Viết',
                    'route' => 'post/index'
                ]
            ]
        ],
        [
            'title' => 'QL Đánh giá',
            'icon' => 'fa fa-comment',
            'name' => ['reviews'],
            'subModule' => [
                [
                    'title' => 'QL Đánh giá',
                    'route' => 'review/index'
                ]
            ]
        ],
        [
            'title' => 'QL Nhóm Thành Viên',
            'icon' => 'fa fa-user',
            'name' => ['user','permission'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm Thành Viên',
                    'route' => 'user/catalogue/index'
                ],
                [
                    'title' => 'QL Thành Viên',
                    'route' => 'user/index'
                ],
                [
                    'title' => 'QL Quyền',
                    'route' => 'permission/index'
                ]
            ]
        ],
        [
            'title' => 'QL Banner & Slide',
            'icon' => 'fa fa-picture-o',
            'name' => ['slide'],
            'subModule' => [
                [
                    'title' => 'Cài đặt Slide',
                    'route' => 'slide/index'
                ],
            ]
        ],
        [
            'title' => 'QL Menu',
            'icon' => 'fa fa-bars',
            'name' => ['menu'],
            'subModule' => [
                [
                    'title' => 'Cài đặt Menu',
                    'route' => 'menu/index'
                ],
            ]
        ],
        [
            'title' => 'Cấu hình chung',
            'icon' => 'fa fa-file',
            'name' => ['language', 'generate', 'system', 'widget'],
            'subModule' => [
                [
                    'title' => 'QL Ngôn ngữ',
                    'route' => 'language/index'
                ],
                // [
                //     'title' => 'QL Module',
                //     'route' => 'generate/index'
                // ],
                [
                    'title' => 'Cấu hình hệ thống',
                    'route' => 'system/index'
                ],
                [
                    'title' => 'Quản lý Widget',
                    'route' => 'widget/index'
                ],
                
            ]
        ]
    ],
];
