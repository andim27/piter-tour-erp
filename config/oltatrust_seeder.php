<?php

return [
    'role_structure' => [
        'superadmin' => [
            'users' => 'c,r,u,d',
            'acl' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'admin' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'user' => [
            'profile' => 'r,u'
        ],
        'director' => [
            'users' => 'c,r,u',
            'profile' => 'r,u'
        ],
        'sales' => [
            'users' => 'r',
            'profile' => 'r'
        ],
        'marketing' => [
            'users' => 'r,u',
        ],
        'reservation' => [
            'users' => 'c,r',
            'profile' => 'r'
        ],
        'accountant' => [
            'users' => 'c,r,u',
            'profile' => 'c,r,u'
        ],


    ],
    'permission_structure' => [
        'cru_user' => [
            'profile' => 'c,r,u',
            'users' => 'c,r,u',
        ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
