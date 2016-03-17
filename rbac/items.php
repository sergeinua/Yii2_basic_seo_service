<?php
return [
    'userRole' => [
        'type' => 2,
        'ruleName' => 'userRole',
    ],
    'guest' => [
        'type' => 1,
        'children' => [
            'userRole',
        ],
    ],
    'seo' => [
        'type' => 1,
        'children' => [
            'guest',
        ],
    ],
    'user' => [
        'type' => 1,
        'children' => [
            'guest',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'seo',
            'user',
        ],
    ],
];
