<?php

return [
    'directory'  => base_path(),
    'repository' => \LeMaX10\Antivirus\Classes\Repository\SnapshotRepository::class,
    'extensions' => ['php', 'js', 'json'],
    'whiteList'  => [
        'bootstrap',
        'modules',
        'config',
        'plugins',
        'themes',
        'index.php',
        '.env',
        '.htaccess',
        '.htpasswd'
    ],
    'excludeList' => [
        '.',
        '..',
        '.git',
        '.github',
        '.idea',
        '.DS_Store',
        'storage',
        'tests'
    ]
];
