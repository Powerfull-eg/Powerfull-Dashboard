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
            'en' => 'Dashboard',
            'ar' => 'لوحة التحكم',
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
            'en' => 'Users Management',
            'ar' => 'إدارة المستخدمين',
        ],
    ],
    [
        'icon' => 'ti ti-help',
        'route' => 'dashboard.support.index',
        'locales' => [
            'en' => 'Support Management',
            'ar' => 'إدارة الدعم',
        ],
    ],
    [
        'icon' => 'ti ti-adjustments',
        'route' => 'dashboard.control.index',
        'locales' => [
            'en' => 'Control Management',
            'ar' => 'إدارة التحكم',
        ],
        "children" => [
            [
                'icon' => 'ti ti-adjustments',
                'route' => 'dashboard.control.index',
                'locales' => [
                    'en' => 'Control Home',
                    'ar' => ' صفحة إدارة التحكم',
                ],
            ],
            [
                'icon' => 'ti ti-bolt',
                'route' => 'dashboard.powerbank.index',
                'locales' => [
                    'en' => 'PowerBank Management',
                    'ar' => 'إدارة أجهزة الباوربانك',
                ],
            ],
            [
                'icon' => 'ti ti-coin',
                'route' => 'dashboard.prices.index',
                'locales' => [
                    'en' => 'Prices Management',
                    'ar' => 'إدارة الأسعار',
                ],
            ],
            [
                'icon' => 'ti ti-certificate',
                'route' => 'dashboard.vouchers.index',
                'locales' => [
                    'en' => 'Vouchers Management',
                    'ar' => 'إدارة الكوبونات',
                ],
            ],
            [
                'icon' => 'ti ti-gift',
                'route' => 'dashboard.gifts.index',
                'locales' => [
                    'en' => 'Gifts Management',
                    'ar' => 'إدارة الهدايا',
                ],
            ],
        ]
    ],
    [
        'route' => 'dashboard.qr-code.index',
        'icon' => 'ti ti-qrcode',
        'locales' => [
            'en' => 'QR Code',
            'ar' => 'رمز الاستجابة السريعة',
        ],
    ],
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
