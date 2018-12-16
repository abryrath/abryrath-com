<?php

return [
    'remotes' => [
        'production' => [
            'host' => 'abryrath.com',
            'username' => 'abry',
            'port' => '22',
            'phpPath' => '/usr/bin/php',
            'root' => '/srv/http/abryrath-com',
            'backupDirectory' => '/srv/http/abryrath-com/storage/backups/databases/',
            'mysqlDumpPath' => '/usr/bin/mysqldump',
        ],
    ],
];
