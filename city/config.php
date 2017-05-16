<?php


return [
    'cachePath' => __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'data.dat',
    'dbData' => [
        'fields' => ['ID' => 1, 'NAME' => 1, 'CODE' => 1],
        'properties' => ['ID' => 1, 'NAME' => 1, 'CODE' => 1, 'VALUE' => 1, '~VALUE' => 1, 'DESCRIPTION' => 1],
        'filter' => ['IBLOCK_ID' => 1, 'ACTIVE_DATE' => 'Y', 'ACTIVE' => 'Y']
    ]
];