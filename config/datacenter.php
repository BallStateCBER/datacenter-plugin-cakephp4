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
        'siteTitle' => '',
        'siteUrl' => '',
        'UserMailer' => [
            'fromEmail' => 'noreply@cberdata.org',
            'fromName' => 'Ball State University Center for Business and Economic Research',
        ],

        // Use full URL or leading slash, e.g. '/img/logo/og_logo.png'
        'defaultOpenGraphLogoPath' => '',
        'defaultTwitterLogoPath' => '',

        'openGraphDescription' => '',
        'facebookAppId' => '',
        'twitterUsername' => '@BallStateCBER',
        'googleAnalyticsId' => '',
    ],
];
