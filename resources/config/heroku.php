<?php

// Doctrine: DB options
$app['db.options'] = array(
    'url' => getenv('DATABASE_URL'),
);

$app['monolog.options'] = [
    'monolog.logfile' => 'php://stderr',
    'monolog.name' => 'app',
    'monolog.level' => 300, // = Logger::WARNING
];
