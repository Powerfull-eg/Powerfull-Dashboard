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
    // [
    //     'route' => 'dashboard.operations',
    //     'icon' => 'ti ti-building-bank',
    //     'locales' => [
    //         'en' => 'Actions',
    //         'ar' => 'العمليات',
    //     ],
    // ],
    // [
    //     'icon' => 'ti ti-battery-automotive',
    //     'locales' => [
    //         'en' => 'Devices',
    //         'ar' => 'المحطات',
    //     ],
    //     'route' => 'dashboard.devices.index',
    // ],
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
