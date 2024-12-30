<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard Sidebar Menu
    |--------------------------------------------------------------------------
    |
    | Here you can define the sidebar menu for your application. It will be
    | displayed on the dashboard page. You can also specify the translation
    | key for each menu item.
    |
    */
    
    // Mainpage (dashboard)
    [
        'route' => 'dashboard.index',
        'icon' => 'ti ti-home',
        'locales' => [
            'en' => 'Home',
            'ar' => 'الصفحة الرئيسية',
        ],
    ],
    // Control
    [
        'icon' => 'ti ti-adjustments',
        'route' => 'dashboard.control.index',
        'locales' => [
            'en' => 'Control',
            'ar' => 'التحكم',
        ],
        "children" => [
            [
                'icon' => 'ti ti-battery-automotive',
                'locales' => [
                    'en' => 'Devices',
                    'ar' => 'المحطات',
                ],
                'route' => 'dashboard.devices.index',
            ],
            [
                'icon' => 'ti ti-building-store',
                'locales' => [
                    'en' => 'Shops',
                    'ar' => 'المتاجر',
                ],
                'route' => 'dashboard.shops.index',
            ],
            [
                'icon' => 'ti ti-users',
                'route' => 'dashboard.users.index',
                'locales' => [
                    'en' => 'Users',
                    'ar' => 'المستخدمين',
                ],
            ],
            [
                'icon' => 'ti ti-gift',
                'route' => 'dashboard.gifts.index',
                'locales' => [
                    'en' => 'Gifts',
                    'ar' => 'الهدايا',
                ],
            ],
            [
                'icon' => 'ti ti-certificate',
                'route' => 'dashboard.vouchers.index',
                'locales' => [
                    'en' => 'Vouchers',
                    'ar' => 'الكوبونات',
                ],
            ],
            [
                'icon' => 'ti ti-coin',
                'route' => 'dashboard.prices.index',
                'locales' => [
                    'en' => 'Pricing',
                    'ar' => 'الأسعار',
                ],
            ],
        ]
    ],
    // Customer Services
    [
        'icon' => 'ti ti-help',
        'route' => 'dashboard.support.index',
        'locales' => [
            'en' => 'Customer Service',
            'ar' => 'خدمة العملاء',
        ],
    ],
    // Action (Operation)
    [
        'route' => 'dashboard.operations.index',
        'icon' => 'ti ti-building-bank',
        'locales' => [
            'en' => 'Operations',
            'ar' => 'العمليات',
        ],
    ],
    // Access Permissions
    [
        'icon' => 'ti ti-login',
        'locales' => [
            'en' => 'Access Permissions',
            'ar' => 'صلاحيات الدخول',
        ],

        'children' => [
            [
                // 'route' => 'dashboard.profile.edit',
                'locales' => [
                    'en' => 'Access Reports',
                    'ar' => 'صلاحيات التقارير',
                ],
            ],

            [
                // 'route' => 'dashboard.roles.index',
                'locales' => [
                    'en' => 'Access Merchants',
                    'ar' => 'صلاحيات المتاجر',
                ],
            ],

            [
                // 'route' => 'dashboard.admins.index',
                'locales' => [
                    'en' => 'Access Selection',
                    'ar' => 'صلاحيات الإختيار',
                ],
            ],
        ],
    ],
    // Reports
    [
        'icon' => 'ti ti-license',
        'locales' => [
            'en' => 'Reports',
            'ar' => 'التقارير',
        ],

        'children' => [
            [
                'route' => 'dashboard.reports.index',
                'locales' => [
                    'en' => 'Comperhensive Reports',
                    'ar' => 'التقارير الكاملة',
                ],
            ],

            [
                'route' => 'dashboard.reports.index',
                'routeData' => ['target' => 'devices'],
                'locales' => [
                    'en' => 'Devices Reports',
                    'ar' => 'تقارير الأجهزة',
                ],
            ],

            [
                'route' => 'dashboard.reports.index',
                'routeData' => ['target' => 'customers'],
                'locales' => [
                    'en' => 'Customers Reports',
                    'ar' => 'تقارير العملاء',
                ],
            ],

            [
                'route' => 'dashboard.reports.index',
                'routeData' => ['target' => 'financial'],
                'locales' => [
                    'en' => 'Financial Reports',
                    'ar' => 'تقارير المالية',
                ],
            ],
        ],
    ],
    // Settings
    [
        'icon' => 'ti ti-settings',
        'locales' => [
            'en' => 'Settings',
            'ar' => 'الإعدادات',
        ],

        'children' => [
            [
                'route' => 'dashboard.profile.edit',
                'locales' => [
                    'en' => 'Profile',
                    'ar' => 'الملف الشخصي',
                ],
            ],

            [
                'route' => 'dashboard.roles.index',
                'locales' => [
                    'en' => 'Roles',
                    'ar' => 'الصلاحيات',
                ],
            ],

            [
                'route' => 'dashboard.admins.index',
                'locales' => [
                    'en' => 'Admins',
                    'ar' => 'المشرفين',
                ],
            ],

            [
                'route' => 'dashboard.settings.edit',
                'locales' => [
                    'en' => 'Settings',
                    'ar' => 'الإعدادات',
                ],
            ],

            [
                'route' => 'dashboard.language.index',
                'locales' => [
                    'en' => 'Language',
                    'ar' => 'اللغة',
                ],
            ],
        ],
    ],

];
