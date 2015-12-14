<?php

$app['debug'] = true;

// Doctrine: DB options
$app['db.options'] = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/../../var/database_test.dat',
);

// Security: User
$app['security.users'] = array('alice' => array('ROLE_USER', 'password'));
