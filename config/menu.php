<?php
/**
 * 菜单的显示
 * Created by PhpStorm.
 * User: wenQing
 * Date: 2016/9/7
 * Time: 16:59
 */
return [
    'MENU_CRM' => [
        [
            'name' => '站点拓展', // 显示名称
            'color' => '#ff834c',
            'cls' => 'shanghu0.png', // 结点css的class，可选
            'sub' => [
                [  // 子结点，可选，最多3级
                    'name' => '首页',
                    'sub' => [
                        [
                            'name' => '首页',
                            'url' => 'index'
                        ],
                        [
                            'name' => '业务设置',
                            'url' => 'test'
                        ]
                    ]
                ],
                [
                    'name' => '信息管理',
                    'sub' => [
                        [
                            'name' => '全部楼盘信息',
                            'url' => '/CRM/Project/index'
                        ],
                        [
                            'name' => '我的楼盘信息',
                            'url' => '/CRM/userProject/index'
                        ]
                    ]
                ],
                [
                    'name' => '业务管理',
                    'sub' => [
                        [
                            'name' => '我的走访',
                            'url' => '/CRM/ProjectVisitLog/myVisit'
                        ],
                        [
                            'name' => '联系人管理',
                            'url' => '/CRM/WuyeContacter/index'
                        ],
                        [
                            'name' => '站点合同管理',
                            'url' => '/CRM/Contract/index'
                        ],
                        [
                            'name' => '物业管理',
                            'url' => '/CRM/WuyeCompany/index'
                        ]
                    ]
                ],
                [
                    'name' => '数据统计',
                    'sub' => [
                        [
                            'name' => '站点流水统计',
                            'url' => '/Setting/AppVersion/index'
                        ],
                        [
                            'name' => '我的业务业绩',
                            'url' => '/Setting/DispatchVersion/index'
                        ]
                    ]
                ],
                [
                    'name' => '账号权限管理',
                    'sub' => [
                        [
                            'name' => '平台角色管理',
                            'url' => '/Manager/RoleManagement/index'
                        ],
                        [
                            'name' => '平台用户管理',
                            'url' => '/Manager/Management/index'
                        ]
                    ]
                ]
            ]
        ],
        [
            'name' => '硬件维护', // 显示名称
            'color' => '#ff834c',
            'cls' => 'yunying.png', // 结点css的class，可选
            'sub' => [
                [  // 子结点，可选，最多3级
                    'name' => '任务管理',
                    'sub' => [
                        [
                            'name' => '任务列表',
                            'url' => '/Hardware/HardwareTask/index'
                        ]
                    ]
                ],
                [  // 子结点，可选，最多3级
                    'name' => '信息管理',
                    'sub' => [
                        [
                            'name' => '便利站基础信息',
                            'url' => '/Hardware/HardwareEquipment/index'
                        ]
                    ]
                ],
                [  // 子结点，可选，最多3级
                    'name' => '统计报表',
                    'sub' => [
                        [
                            'name' => '便利站问题统计',
                            'url' => '/Hardware/HardwareCount/marketQuestion'
                        ],
                        [
                            'name' => '门店问题统计',
                            'url' => '/Hardware/HardwareCount/storeQuestion'
                        ],
                        [
                            'name' => '人员任务统计',
                            'url' => '/Hardware/HardwareCount/userTask'
                        ]
                    ]
                ],
                [  // 子结点，可选，最多3级
                    'name' => '权限管理',
                    'sub' => [
                        [
                            'name' => '站点问题统计',
                            'url' => '/CRM/Index/index'
                        ]
                    ]
                ],
                [  // 子结点，可选，最多3级
                    'name' => '工作宝典',
                    'sub' => [
                        [
                            'name' => '工作宝典设置',
                            'url' => '/Hardware/HardwareWorkCanon/index'
                        ]
                    ]
                ]
            ]
        ],
        [
            'name' => '商家CRM', // 显示名称
            'color' => '#ff834c',
            'cls' => 'yunying.png', // 结点css的class，可选
            'sub' => [
                [  // 子结点，可选，最多3级
                    'name' => '协同布站',
                    'sub' => [
                        [
                            'name' => '开栈列表',
                            'url' => '/BusinessCRM/ClothStation/index'
                        ]
                    ]
                ],
                [  // 子结点，可选，最多3级
                    'name' => '首页',
                    'sub' => [
                        [
                            'name' => '便利站基础信息',
                            'url' => '/Hardware/HardwareEquipment/index'
                        ]
                    ]
                ],
                [  // 子结点，可选，最多3级
                    'name' => '门店信息管理',
                    'sub' => [
                        [
                            'name' => '便利站问题统计',
                            'url' => '/Hardware/HardwareCount/marketQuestion'
                        ],
                        [
                            'name' => '门店问题统计',
                            'url' => '/Hardware/HardwareCount/storeQuestion'
                        ],
                        [
                            'name' => '人员任务统计',
                            'url' => '/Hardware/HardwareCount/userTask'
                        ]
                    ]
                ],
                [  // 子结点，可选，最多3级
                    'name' => '业务管理',
                    'sub' => [
                        [
                            'name' => '商家合同信息',
                            'url' => '/BusinessCRM/BusinessContract/index'
                        ]
                    ]
                ]
            ]
        ],
        [
            'name' => '系统', // 显示名称
            'color' => '#7fa936',
            'cls' => 'xitong.png', // 结点css的class，可选
            'sub' => [
                [
                    'name' => '账号权限管理',
                    'sub' => [
                        [
                            'name' => '平台角色管理',
                            'url' => '/Manager/RoleManagement/index'
                        ],
                        [
                            'name' => '平台用户管理',
                            'url' => '/Manager/Management/index'
                        ]
                    ]
                ],
                [  // 子结点，可选，最多3级
                    'name' => '系统设置',
                    'sub' => [
                        [
                            'name' => '区域信息设置',
                            'url' => 'area/index'
                        ]
                    ]
                ]
            ]
        ]

    ]

];