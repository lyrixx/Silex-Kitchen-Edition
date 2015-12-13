<?php

$app['debug'] = true;

// Doctrine (DB)
$app['db.options'] = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/../db/database_test.dat',
);

$app['security.users'] = array('alice' => array('ROLE_USER', 'password'));
