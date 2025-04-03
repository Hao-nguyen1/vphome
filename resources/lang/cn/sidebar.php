<?php   
return [
    'module' => [
        [
            'title' => '文章',  // Article
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => '文章组',  // Article Group
                    'route' => 'post/catalogue/index'
                ],
                [
                    'title' => '文章',  // Article
                    'route' => 'post/index'
                ]
            ]
        ],
        [
            'title' => '用户组',  // User Group
            'icon' => 'fa fa-user',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => '用户组',  // User Group
                    'route' => 'user/catalogue/index'
                ],
                [
                    'title' => '用户',  // User
                    'route' => 'user/index'
                ],
                [
                    'title' => '权限',
                    'route' => 'permission/index'
                ]
            ]
        ],
        [
            'title' => '通用',  // General
            'icon' => 'fa fa-file',
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => '语言',  // Language
                    'route' => 'language/index'
                ],
            ]
        ]
    ],
];


