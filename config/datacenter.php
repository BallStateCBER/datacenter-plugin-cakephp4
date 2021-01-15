<?php
return [
    'DataCenter' => [
        'auth' => [
            'enabled' => false,
            'loginUrl' => [
                'prefix' => false,
                'plugin' => null,
                'controller' => 'Users',
                'action' => 'login',
            ],
            'logoutRedirect' => [
                'prefix' => false,
                'plugin' => null,
                'controller' => 'Users',
                'action' => 'login',
            ],
        ],
    ],
];
