<?php


return [
    'cachePath' => __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'data.dat',
    'dbData' => [
        'fields' => ['ID' => 1, 'IBLOCK_ID' => 1, 'NAME' => 1, 'CODE' => 1],
        'properties' => [],
        'filter' => ['IBLOCK_ID' => [5,6], 'ACTIVE_DATE' => 'Y', 'ACTIVE' => 'Y']
    ],
    'iblocks' => [
        'langs' => [
            'ru' => 5,
            'en' => 6
        ],
        'cities' => [
            'ru' => 5,
            'en' => 6
        ],
        'tariffs' => [
            'ru' => 11,
            'en' => 12
        ],
        'about_us' => [
            'ru' => 27,
            'en' => 28
        ],
        'contacts' => [
            'ru' => 29,
            'en' => 30
        ],
        'jobs' => [
            'ru' => 15,
            'en' => 16
        ],
        'news' => [
            'ru' => 3,
            'en' => 4
        ],
        'forgotten_things'  => [
            'ru' => 19,
            'en' => 20
        ],
        'reviews'  => [
            'ru' => 19,
            'en' => 20
        ],
    ]
];