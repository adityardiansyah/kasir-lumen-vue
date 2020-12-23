<?php

return [
    'providers' => [
        App\Providers\GlobalHelperServiceProvider::class,
    ],
    'alias' => [
        'GlobalHelper' => App\Helper\GlobalHelper::class
    ]
];